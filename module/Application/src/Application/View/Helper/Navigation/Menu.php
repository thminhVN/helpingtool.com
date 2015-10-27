<?php
/**
 * Application
 */

namespace Application\View\Helper\Navigation;

use RecursiveIteratorIterator;
use Zend\View\Helper\Navigation\Menu as ZendMenu;
use Zend\Navigation\AbstractContainer;
use Zend\Navigation\Page\AbstractPage;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Helper for rendering menus from navigation containers
 */
class Menu extends ZendMenu implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    /**
     * CSS class to use for the ul element
     *
     * @var string
     */
    protected $ulClass = 'nav';
    
    /**
     * CSS id to use for the ul element
     *
     * @var string
     */
    protected $ulId = '';

    
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator->getServiceLocator();
    }
    
    private function _getCurrentLanguage()
    {
        $routeInfo = $this->getServiceLocator()
        ->get('viewhelpermanager')->get('routeinfo');
        return $routeInfo->get('lang');
    }
    
    /**
     * Renders the deepest active menu within [$minDepth, $maxDepth], (called
     * from {@link renderMenu()})
     *
     * @param  AbstractContainer $container          container to render
     * @param  string            $ulClass            CSS class for first UL
     * @param  string            $indent             initial indentation
     * @param  int|null          $minDepth           minimum depth
     * @param  int|null          $maxDepth           maximum depth
     * @param  bool              $escapeLabels       Whether or not to escape the labels
     * @param  bool              $addClassToListItem Whether or not page class applied to <li> element
     * @return string
     */
    protected function renderDeepestMenu(
        AbstractContainer $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth,
        $escapeLabels,
        $addClassToListItem,
        $liActiveClass = null
    ) {
        if (!$active = $this->findActive($container, $minDepth - 1, $maxDepth)) {
            return '';
        }

        // special case if active page is one below minDepth
        if ($active['depth'] < $minDepth) {
            if (!$active['page']->hasPages()) {
                return '';
            }
        } elseif (!$active['page']->hasPages()) {
            // found pages has no children; render siblings
            $active['page'] = $active['page']->getParent();
        } elseif (is_int($maxDepth) && $active['depth'] +1 > $maxDepth) {
            // children are below max depth; render siblings
            $active['page'] = $active['page']->getParent();
        }

        $ulClass = $ulClass ? ' class="' . $ulClass . '"' : '';
        $html = $indent . '<ul' . $ulClass . '>' . self::EOL;

        foreach ($active['page'] as $subPage) {
            if (!$this->accept($subPage)) {
                continue;
            }

            // render li tag and page
            $liClasses = array();
            // Is page active?
            if ($subPage->isActive(true)) {
                $liClasses[] = 'active';
            }

            // Add CSS class from page to <li>
            if ($addClassToListItem && $subPage->getClass()) {
                $liClasses[] = $subPage->getClass();
            }
            $liClass = empty($liClasses) ? '' : ' class="' . implode(' ', $liClasses) . '"';

            $html .= $indent . '    <li' . $liClass . '>' . self::EOL;
            $html .= $indent . '        ' . $this->htmlify($subPage, $escapeLabels, $addClassToListItem) . self::EOL;
            $html .= $indent . '    </li>' . self::EOL;
        }

        $html .= $indent . '</ul>';

        return $html;
    }

    /**
     * Renders a normal menu (called from {@link renderMenu()})
     *
     * @param  AbstractContainer $container          container to render
     * @param  string            $ulClass            CSS class for first UL
     * @param  string            $indent             initial indentation
     * @param  int|null          $minDepth           minimum depth
     * @param  int|null          $maxDepth           maximum depth
     * @param  bool              $onlyActive         render only active branch?
     * @param  bool              $escapeLabels       Whether or not to escape the labels
     * @param  bool              $addClassToListItem Whether or not page class applied to <li> element
     * @return string
     */
    protected function renderNormalMenu(
        AbstractContainer $container,
        $ulClass,
        $indent,
        $minDepth,
        $maxDepth,
        $onlyActive,
        $escapeLabels,
        $addClassToListItem,
        $liActiveClass = null
    ) {
        $html = '';

        // find deepest active
        $found = $this->findActive($container, $minDepth, $maxDepth);
        if ($found) {
            $foundPage  = $found['page'];
            $foundDepth = $found['depth'];
        } else {
            $foundPage = null;
        }

        // create iterator
        $iterator = new RecursiveIteratorIterator($container,
            RecursiveIteratorIterator::SELF_FIRST);
        if (is_int($maxDepth)) {
            $iterator->setMaxDepth($maxDepth);
        }

        // iterate container
        $prevDepth = -1;
        foreach ($iterator as $page) {
            $depth = $iterator->getDepth();
            $route = $page->get('route');
            if(!empty($route)){
                $route = str_replace('%lang%', $this->_getCurrentLanguage(), $route);
                $page->set('route', $route);
            }
            $isActive = $page->isActive(true);
            if ($depth < $minDepth || !$this->accept($page)) {
                // page is below minDepth or not accepted by acl/visibility
                continue;
            } elseif ($onlyActive && !$isActive) {
                // page is not active itself, but might be in the active branch
                $accept = false;
                if ($foundPage) {
                    if ($foundPage->hasPage($page)) {
                        // accept if page is a direct child of the active page
                        $accept = true;
                    } elseif ($foundPage->getParent()->hasPage($page)) {
                        // page is a sibling of the active page...
                        if (!$foundPage->hasPages() ||
                            is_int($maxDepth) && $foundDepth + 1 > $maxDepth) {
                            // accept if active page has no children, or the
                            // children are too deep to be rendered
                            $accept = true;
                        }
                    }
                }

                if (!$accept) {
                    continue;
                }
            }

            // make sure indentation is correct
            $depth -= $minDepth;
            $myIndent = $indent . str_repeat('        ', $depth);

            if ($depth > $prevDepth) {
                // start new ul tag
                if ($ulClass && $depth ==  0) {
                    $ulClass = ' class="' . $ulClass . '"';
                    $ulClass .= ' id="'. $this->getUlId().'"';
                } else {
                    $ulClass = ' class="'. $page->getParent()->get('ulClass') . '"';
                }
                $html .= $myIndent . '<ul' . $ulClass . '>' . self::EOL;
            } elseif ($prevDepth > $depth) {
                // close li/ul tags until we're at current depth
                for ($i = $prevDepth; $i > $depth; $i--) {
                    $ind = $indent . str_repeat('        ', $i);
                    $html .= $ind . '    </li>' . self::EOL;
                    $html .= $ind . '</ul>' . self::EOL;
                }
                // close previous li tag
                $html .= $myIndent . '    </li>' . self::EOL;
            } else {
                // close previous li tag
                $html .= $myIndent . '    </li>' . self::EOL;
            }

            // render li tag and page
            $liClasses = array();
            // Is page active?
            if ($isActive) {
                $liClasses[] = 'active';
            }
            // Is page parent?
            if ($page->hasChildren() && (!isset($maxDepth) || $depth < $maxDepth)) {
                $liClasses[] = $page->get('liClass');
            }
            // Add CSS class from page to <li>
            if ($addClassToListItem && $page->getClass()) {
                $liClasses[] = $page->getClass();
            }
            $liClass = empty($liClasses) ? '' : ' class="' . implode(' ', $liClasses) . '"';

            $html .= $myIndent . '    <li' . $liClass . '>' . self::EOL
                . $myIndent . '        ' . $this->htmlify($page, $escapeLabels, $addClassToListItem) . self::EOL;

            // store as previous depth for next iteration
            $prevDepth = $depth;
        }

        if ($html) {
            // done iterating container; close open ul/li tags
            for ($i = $prevDepth+1; $i > 0; $i--) {
                $myIndent = $indent . str_repeat('        ', $i-1);
                $html .= $myIndent . '    </li>' . self::EOL
                    . $myIndent . '</ul>' . self::EOL;
            }
            $html = rtrim($html, self::EOL);
        }

        return $html;
    }

    /**
     * Returns an HTML string containing an 'a' element for the given page if
     * the page's href is not empty, and a 'span' element if it is empty
     *
     * Overrides {@link AbstractHelper::htmlify()}.
     *
     * @param  AbstractPage $page               page to generate HTML for
     * @param  bool         $escapeLabel        Whether or not to escape the label
     * @param  bool         $addClassToListItem Whether or not to add the page class to the list item
     * @return string
     */
    public function htmlify(AbstractPage $page, $escapeLabel = true, $addClassToListItem = false)
    {
        // get label and title for translating
        $label = $page->getLabel();
        $title = $page->getTitle();
        $lang = self::_getCurrentLanguage();
        if($lang) {
            $translator =  $this->getTranslator();
            if ($translator)
                $translator->setLocale($lang);
        }


        // translate label and title?
        if (null !== ($translator = $this->getTranslator())) {
            $textDomain = $this->getTranslatorTextDomain();
            if (is_string($label) && !empty($label)) {
                $label = $translator->translate($label, $textDomain);
            }
            if (is_string($title) && !empty($title)) {
                $title = $translator->translate($title, $textDomain);
            }
        }

        // get attribs for element
        $element = 'a';
        $extended = '';
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
            'href'   => '#',
        );

        $class = array();
        if ($addClassToListItem === false) {
            $class[] = $page->getClass();
        }

        $attribs = $page->get('attribute');
        $extended = $page->get('extended');

        if (count($class) > 0) {
            $attribs['class'] = implode(' ', $class);
        }

        $route = $page->get('route');
        if(!empty($route)){
            $route = str_replace('%lang%', $this->_getCurrentLanguage(), $route);
            $page->set('route', $route);
        }
        // does page have a href?
        $href = $page->getHref();
        if ($href) {
            $attribs['href'] = $href;
            $attribs['target'] = $page->getTarget();
        }

        $html = '<' . $element . $this->htmlAttribs($attribs) . '>';
        if(isset($extended['before']))
        {
            $html .= $extended['before'];
        }
        if ($escapeLabel === true) {
            $escaper = $this->view->plugin('escapeHtml');
            $html .= $escaper($label);
        } else {
            $html .= $label;
        }
        if(isset($extended['after']))
        {
            $html .= $extended['after'];
        }
        $html .= '</' . $element . '>';

        return $html;
    }

    public function setUlId($id)
    {
        $this->ulId = $id;
        return $this;
    }

    protected function getUlId()
    {
        return $this->ulId;
    }
}
