/**
 * All Global functions required to whole site.
 */
var Common = {
    contexts : {
        'boxNavigation': '.box-navigation',
        'btnExplore': '.box-navigation .btn-explore'
    },

    initialize: function() {
        Common.OnClickExplore();
    },

    /* On clicking map btn in nav, it will redirect to default home on mobile */
    OnClickExplore: function(){
        var target = $(Common.contexts.btnExplore);
        target.unbind();
        target.on('click',function(e){
            if(isMobile){
                sessionStorage.show_landing = 1;
                window.location.href = baseUrl + "/netwrk/default/home";
            }else{
                console.log('common.OnClickExplore');
            }
        });
    }
};
