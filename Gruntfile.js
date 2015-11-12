module.exports = function (grunt) {
    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                mangle: false
            },
            build: {
                files: {
                    './public/js/app.min.js': [
                    /** jQuery **/
                        './bower_components/jquery/dist/jquery.js',
                    /** Bootstrap **/
                        './bower_components/bootstrap/js/dropdown.js',
                        './bower_components/bootstrap/js/alert.js',
                        './bower_components/bootstrap/js/transition.js',
                        './bower_components/bootstrap/js/collapse.js',
                    /** App's scripts **/
                        './libs/js/app.js'
                    ],
                    './public/js/connexion.min.js': [
                    /** jQuery **/
                        './bower_components/jquery/dist/jquery.js',
                    /** Bootstrap **/
                        './bower_components/bootstrap/js/dropdown.js',
                        './bower_components/bootstrap/js/alert.js'
                    ],
                    './public/js/ie.min.js': [
                    /** HTML5SHIV **/
                        './bower_components/html5shiv/dist/html5shiv.js',
                    /** Respond **/
                        './bower_components/respond-minmax/src/*'
                    ]
                }
            }
        },
        less: {
            core: {
                files: {
                    './libs/less/compiled/app.css': './libs/less/app.less',
                    './libs/less/compiled/connexion.css': './libs/less/connexion.less'
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    './public/css/app.min.css': [
                    /** BOOTSWATCH **/
                        './libs/less/compiled/bootstrap.min.css',
                    /** LESS **/
                        './libs/less/compiled/app.css',
                    /** Font Awesome **/
                        './bower_components/fontawesome/css/font-awesome.css'
                    ],
                    './public/css/connexion.min.css': [
                    /** BOOTSWATCH **/
                        './libs/less/compiled/bootstrap.min.css',
                    /** LESS **/
                        './libs/less/compiled/connexion.css',
                    /** Font Awesome **/
                        './bower_components/fontawesome/css/font-awesome.css'
                    ]
                }
            }
        },
        copy: {
            main: {
                files: [
                    {
                        expand: true,
                        cwd: './bower_components/fontawesome/fonts/',
                        src: '*.*',
                        dest: './public/fonts/',
                        filter: 'isFile'
                    }
                ]
            }
        }
    });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');
    // Load the plugin that provides the "less" task.
    grunt.loadNpmTasks('grunt-contrib-less');
    // Load the plugin that provides the "cssmin" task.
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    // Load the plugin that provides the "copy" task.
    grunt.loadNpmTasks('grunt-contrib-copy');

    // Default task(s).
    grunt.registerTask('default', ['uglify', 'less', 'cssmin', 'copy']);
    grunt.registerTask('css', ['less', 'cssmin', 'copy']);
    grunt.registerTask('js', ['uglify']);
};