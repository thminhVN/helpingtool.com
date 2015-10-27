<?php
/**
 * Global Configuration
 *
 * This configuration store all asset file to manage on your website
 */

return array(
    'assets_bundle' => array(
        'mediaExt' => array(
            'jpeg',
            'jpg',
            'png',
            'gif',
            'cur',
            'ttf',
            'eot',
            'svg',
            'woff',
            'woff2',
        ),
        'production' => false,
        'assets' => array(
            'css' => array(
                '/lib/bootstrap/css/bootstrap.min.css',
                '/lib/font-awesome/css/font-awesome.min.css',
                '/css/common.css',
            ),
            'js' => array(
                '/js/html5shiv.js',
                '/js/respond.min.js',
                '/js/jquery.min.js',
                '/lib/bootstrap/js/bootstrap.min.js'
            ),
            'media' => array(
                'img',
                'lib/bootstrap/fonts',
                'lib/font-awesome/fonts',
                'fonts',
            ),
            //Modules specific assets
            'Application' => array(
                'css' => array(
                    '/css/style.css',
                ),
                'js' => array(
                    '/js/application/main.js',
                ),
                //Controller specific assets
                'Application\Controller\Auth' => array(
                    'css' => array(
                        '/css/application/auth.css'
                    ),
                ),
                'Application\Controller\Index' => array(
                    'index' => array(
                        'css' => array(
                            '/css/application/home.css'
                        ),
                        'js' => array(
                            '/js/application/slider.js'
                        )
                    ),
                )
            ),
            'Admin' => array(
                'css' => array(
                    '/lib/datatable/css/dataTables.bootstrap.min.css',
                    '/lib/jquery-ui/css/jquery-ui.min.css',
                    '/lib/colorbox/colorbox.css',
                    '/lib/bootstrap-plugin/datetimepicker/css/bootstrap-datetimepicker.min.css',
                    '/css/admin',
                ),
                'js' => array(
                    '/lib/datatable/js/jquery.dataTables.min.js',
                    '/lib/datatable/js/dataTables.bootstrap.min.js',
                    '/lib/bootstrap-plugin/bootbox.min.js',
                    '/lib/jquery-ui/js/jquery-ui.min.js',
                    '/lib/colorbox/jquery.colorbox-min.js',
                    '/lib/bootstrap-plugin/datetimepicker/js/moment.js',
                    '/lib/bootstrap-plugin/datetimepicker/js/bootstrap-datetimepicker.min.js',
                    '/lib/metisMenu.min.js',
                    '/lib/ckeditor/ckeditor.js',
                    '/lib/ckeditor',
                ),
                'media' => array(
                    '/lib/colorbox/images',
                    '/lib/datatable/images',
                    '/lib/ckeditor',
                ),
                'Admin\Controller\Media' => array(
                    'css' => array(
                        '/lib/jstree/themes/default/style.min.css',
                        '/lib/fileupload/css/jquery.fileupload.css',
                        '/lib/fileupload/css/jquery.fileupload-ui.css',
                    ),
                    'js' => array(
                        '/lib/jstree/jstree.min.js',
                        '/lib/fileupload/js/vendor/jquery.ui.widget.js',
                        '/lib/fileupload/js/tmpl.min.js',
                        '/lib/fileupload/js/load-image.min.js',
                        '/lib/fileupload/js/canvas-to-blob.min.js',
                        '/lib/fileupload/js/jquery.blueimp-gallery.min.js',
                        '/lib/fileupload/js/jquery.iframe-transport.js',
                        '/lib/fileupload/js/jquery.fileupload.js',
                        '/lib/fileupload/js/jquery.fileupload-process.js',
                        '/lib/fileupload/js/jquery.fileupload-image.js',
                        '/lib/fileupload/js/jquery.fileupload-audio.js',
                        '/lib/fileupload/js/jquery.fileupload-video.js',
                        '/lib/fileupload/js/jquery.fileupload-validate.js',
                        '/lib/fileupload/js/jquery.fileupload-ui.js',
                        '/lib/fileupload/js/main.js',
                    ),
                    'media' => array(
                        '/lib/jstree/themes/default',
                        '/lib/fileupload/img'
                    ),
                ),
                
            )
        )
    ),
);
