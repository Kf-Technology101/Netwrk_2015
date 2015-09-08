module.exports = function(grunt) {
  require('jit-grunt')(grunt);

  grunt.initConfig({
    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          "frontend/web/css/bootstrap.css": "frontend/web/less/bootstrap/bootstrap.less", // destination file and source file
          "frontend/web/css/landing.css": "frontend/web/less/landing.less",
          "frontend/web/css/mobile.css": "frontend/web/less/mobile.less"
        }
      }
    },
    watch: {
      styles: {
        files: ['frontend/web/less/**/*.less', 'backend/web/less/**/*.less'], // which files to watch
        tasks: ['less'],
        options: {
          nospawn: true
        }
      }
    }
  });

  grunt.registerTask('default', ['less', 'watch']);
};
