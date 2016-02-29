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
          "frontend/web/css/desktop/landing.css": "frontend/web/less/desktop/landing.less",
          "frontend/web/css/desktop/topic.css": "frontend/web/less/desktop/topic.less",
          "frontend/web/css/desktop/groups_loc.css": "frontend/web/less/desktop/groups_loc.less",
          "frontend/web/css/desktop/meet.css": "frontend/web/less/desktop/meet.less",
          "frontend/web/css/desktop/login.css": "frontend/web/less/desktop/login.less",
          "frontend/web/css/desktop/chat_post.css": "frontend/web/less/desktop/chat_post.less",
          "frontend/web/css/desktop/post.css": "frontend/web/less/desktop/post.less",
          "frontend/web/css/desktop/groups.css": "frontend/web/less/desktop/groups.less",
          "frontend/web/css/desktop/signup.css": "frontend/web/less/desktop/signup.less",
          "frontend/web/css/desktop/chat_inbox.css": "frontend/web/less/desktop/chat_inbox.less",
          "frontend/web/css/desktop/forgot_pass.css": "frontend/web/less/desktop/forgot_pass.less",
          "frontend/web/css/desktop/popup_chat.css": "frontend/web/less/desktop/popup_chat.less",
          "frontend/web/css/desktop/search.css": "frontend/web/less/desktop/search.less",
          "frontend/web/css/desktop/marker_popup.css": "frontend/web/less/desktop/marker_popup.less",
          "frontend/web/css/desktop/dropdown_avatar.css": "frontend/web/less/desktop/dropdown_avatar.less",
          "frontend/web/css/desktop/landing_page.css": "frontend/web/less/desktop/landing_page.less",
          "frontend/web/css/desktop/cover_page.css": "frontend/web/less/desktop/cover_page.less",
          "frontend/web/css/desktop/profile.css": "frontend/web/less/desktop/profile.less",
          "frontend/web/css/desktop/password_setting.css": "frontend/web/less/desktop/password_setting.less",
          "frontend/web/css/desktop/search_setting.css": "frontend/web/less/desktop/search_setting.less",
          // CSS on Mobile
          "frontend/web/css/mobile/landing.css": "frontend/web/less/mobile/landing.less",
          "frontend/web/css/mobile/topic.css": "frontend/web/less/mobile/topic.less",
          "frontend/web/css/mobile/meet.css": "frontend/web/less/mobile/meet.less",
          "frontend/web/css/mobile/setting.css": "frontend/web/less/mobile/setting.less",
          "frontend/web/css/mobile/post.css": "frontend/web/less/mobile/post.less",
          "frontend/web/css/mobile/chat_post.css": "frontend/web/less/mobile/chat_post.less",
          "frontend/web/css/mobile/login.css": "frontend/web/less/mobile/login.less",
          "frontend/web/css/mobile/signup.css": "frontend/web/less/mobile/signup.less",
          "frontend/web/css/mobile/chat_inbox.css": "frontend/web/less/mobile/chat_inbox.less",
          "frontend/web/css/mobile/forgot_pass.css": "frontend/web/less/mobile/forgot_pass.less",
          "frontend/web/css/mobile/search.css": "frontend/web/less/mobile/search.less",
          "frontend/web/css/mobile/dropdown_avatar.css": "frontend/web/less/mobile/dropdown_avatar.less",
          "frontend/web/css/mobile/landing_page.css": "frontend/web/less/mobile/landing_page.less",
          "frontend/web/css/mobile/cover_page.css": "frontend/web/less/mobile/cover_page.less",
          "frontend/web/css/mobile/groups.css": "frontend/web/less/mobile/groups.less",
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
