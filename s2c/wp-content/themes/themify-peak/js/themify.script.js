( ($,Themify,doc)=> {
    'use strict';
	function doInfinite(container, is_shortcode) {
		Themify.infinity(container,{
                    scrollThreshold:!is_shortcode && !('auto' !== themifyScript.autoInfinite),
                    history: is_shortcode || !themifyScript.infiniteURL?false:'replace'
		});
		if(is_shortcode){
                    container.tfOn('infiniteloaded',e=>{
                        const items=e.detail.items,
                            last=items[items.length-1];
                            if('load-more'===last.id){
                                    last.remove();
                            }
                    },{passive:true});
		}
	}

		const $body = Themify.body;
		/////////////////////////////////////////////
		// Initialize Packery Layout and Filter
		/////////////////////////////////////////////
		if (doc.body.classList.contains('fixed-header-enabled')) {
			Themify.fixedHeader();
		}
		
                Themify.megaMenu(doc.tfId('main-nav'));
		$('.loops-wrapper').each(function () {
			//infinite
			let load = this.querySelector('#load-more');
			if (load!==null) {
				if(this.nextElementSibling!==null && this.nextElementSibling.classList.contains('load-more-button')){
					load.parentNode.removeChild(load);
					load=this.nextElementSibling;
				}
				else{
					this.after(load);
				}
				doInfinite(this,true);
			}
			else if (this.nextElementSibling!==null && this.nextElementSibling.querySelector('.load-more-button')) {
				doInfinite(this, false);
			}
		});
		

		///////////////////////////////////////////
		// Initialize infinite scroll
		///////////////////////////////////////////
		if (doc.body.classList.contains('woocommerce') && doc.body.classList.contains('archive')) {
			doInfinite(doc.querySelectorAll('#content ul.products'));
		}
		/////////////////////////////////////////////
		// Scroll to top 							
		/////////////////////////////////////////////
		$('.back-top a').on('click',e=> {
			e.preventDefault();
			Themify.scrollTo();
		});

		Themify.sideMenu(doc.tfId('menu-icon'),{
			close: '#menu-icon-close'
		});

		Themify.on('tfsmartresize',e=>{
                    if(e){
			if ( e.w > themify_vars['menu_point']) {
				$('#main-nav li.has-mega-column > .mega-column-wrapper, #main-nav li.has-mega-sub-menu > .mega-sub-menu').css('width',  $('#header').width());
			} else {
				$('#main-nav li.has-mega-column > .mega-column-wrapper,#main-nav li.has-mega-sub-menu > .mega-sub-menu').removeAttr('style');
			}
                    }
		});

		let $mainMenu = $('#main-nav-wrap, #searchform-wrap, .social-widget'), // Move menu into side panel on small screens
                    sbarParent, sbarChild, sbarWidth;

		if (sbarWidth === undefined) {
			sbarParent = $('<div class="scrollbar-parent"><div/></div>').appendTo($body);
			sbarChild = sbarParent.children();
			sbarWidth = sbarChild.innerWidth() - sbarChild.height(99).innerWidth();
			sbarParent.remove();
		}

		if ($mainMenu.length > 0) {
			const $mobileNavWrap = $('.slideout-widgets'),
                            $desktopNavWrap = doc.tfId('menu-wrapper'),
			//on load check mobile menu trigger point and set the ul menu position
                        checkMobileMenu=()=>{
				const w = $("#mobile-menu").hasClass("sidemenu-on")?$(window).outerWidth():($(window).outerWidth() + sbarWidth);
				if (w <= themify_vars['menu_point']) {
					$mainMenu.detach().insertBefore($mobileNavWrap);
				} else {
					$mainMenu.detach().prependTo($desktopNavWrap);
				}
			};
                        Themify.on('tfsmartresize',e=>{
                            setTimeout(checkMobileMenu,100);
                        });
			checkMobileMenu();
		}
    doc.tfOn('click',async e=>{
       const btn=e.target.closest('.likeit');
       if(btn){
            e.preventDefault();
            const data=await Themify.fetch({
                action: 'themify_likeit',
                nonce: themifyScript.ajax_nonce,
                post_id: btn.dataset.postid
            }),
            $parent = $(btn.parentNode);
            if ('new' === data.status) {
                    $('.newliker', $parent).fadeIn();
                    $('ins', btn).fadeOut('slow', function () {
                            $(this).text(data.likers).fadeIn('slow');
                    });
            }
            else if ('isliker' === data.status) {
                    $('.newliker', $parent).hide();
                    $('.isliker', $parent).fadeIn();
            }
       }
    });

	
})(jQuery,Themify,document);
