/*!
=========================================================
AppJS
=========================================================
*/

$(function () {
  "use strict";

  var color = "#5e72e4";
  var Layout = function() {
    function e() {
      $(".sidenav-toggler").addClass("active"), $(".sidenav-toggler").data("action", "sidenav-unpin"), $("body").removeClass("g-sidenav-hidden").addClass("g-sidenav-show g-sidenav-pinned"), $("body").append('<div class="backdrop d-xl-none" data-action="sidenav-unpin" data-target=' + $("#sidenav-main").data("target") + " />"), Cookies.set("sidenav-state", "pinned")
    }

    function a() {
      $(".sidenav-toggler").removeClass("active"), $(".sidenav-toggler").data("action", "sidenav-pin"), $("body").removeClass("g-sidenav-pinned").addClass("g-sidenav-hidden"), $("body").find(".backdrop").remove(), Cookies.set("sidenav-state", "unpinned")
    }
    var t = Cookies.get("sidenav-state") ? Cookies.get("sidenav-state") : "pinned";
    $(window).width() > 1200 && ("pinned" == t && e(), "unpinned" == Cookies.get("sidenav-state") && a(), $(window).resize(function() {
      $("body").hasClass("g-sidenav-show") && !$("body").hasClass("g-sidenav-pinned") && $("body").removeClass("g-sidenav-show").addClass("g-sidenav-hidden")
    })), $(window).width() < 1200 && ($("body").removeClass("g-sidenav-hide").addClass("g-sidenav-hidden"), $("body").removeClass("g-sidenav-show"), $(window).resize(function() {
      $("body").hasClass("g-sidenav-show") && !$("body").hasClass("g-sidenav-pinned") && $("body").removeClass("g-sidenav-show").addClass("g-sidenav-hidden")
    })), $("body").on("click", "[data-action]", function(t) {
      t.preventDefault();
      var n = $(this),
        i = n.data("action");
      n.data("target");
      switch (i) {
        case "sidenav-pin":
          e();
          break;
        case "sidenav-unpin":
          a();
          break;
        case "search-show":
          n.data("target"), $("body").removeClass("g-navbar-search-show").addClass("g-navbar-search-showing"), setTimeout(function() {
            $("body").removeClass("g-navbar-search-showing").addClass("g-navbar-search-show")
          }, 150), setTimeout(function() {
            $("body").addClass("g-navbar-search-shown")
          }, 300);
          break;
        case "search-close":
          n.data("target"), $("body").removeClass("g-navbar-search-shown"), setTimeout(function() {
            $("body").removeClass("g-navbar-search-show").addClass("g-navbar-search-hiding")
          }, 150), setTimeout(function() {
            $("body").removeClass("g-navbar-search-hiding").addClass("g-navbar-search-hidden")
          }, 300), setTimeout(function() {
            $("body").removeClass("g-navbar-search-hidden")
          }, 500)
      }
    }), $(".sidenav").on("mouseenter", function() {
      $("body").hasClass("g-sidenav-pinned") || $("body").removeClass("g-sidenav-hide").removeClass("g-sidenav-hidden").addClass("g-sidenav-show")
    }), $(".sidenav").on("mouseleave", function() {
      $("body").hasClass("g-sidenav-pinned") || ($("body").removeClass("g-sidenav-show").addClass("g-sidenav-hide"), setTimeout(function() {
        $("body").removeClass("g-sidenav-hide").addClass("g-sidenav-hidden")
      }, 300))
    }), $(window).on("load resize", function() {
      $("body").height() < 800 && ($("body").css("min-height", "100vh"), $("#footer-main").addClass("footer-auto-bottom"))
    })
  }();

  var Navbar = function() {
    var e = $(".navbar-nav, .navbar-nav .nav"),
      a = $(".navbar .collapse"),
      t = $(".navbar .dropdown");
    a.on({
      "show.bs.collapse": function() {
        var t;
        (t = $(this)).closest(e).find(a).not(t).collapse("hide")
      }
    }), t.on({
      "hide.bs.dropdown": function() {
        var e, a;
        e = $(this), (a = e.find(".dropdown-menu")).addClass("close"), setTimeout(function() {
          a.removeClass("close")
        }, 200)
      }
    })
  }();
  
  var NavbarCollapse = function() {
    $(".navbar-nav");
    var e = $(".navbar .navbar-custom-collapse");
    e.length && (e.on({
      "hide.bs.collapse": function() {
        e.addClass("collapsing-out")
      }
    }), e.on({
      "hidden.bs.collapse": function() {
        e.removeClass("collapsing-out")
      }
    }))
  }();

  var Popover = function() {
    var e = $('[data-toggle="popover"]'),
      a = "";
    e.length && e.each(function() {
      ! function(e) {
        e.data("color") && (a = "popover-" + e.data("color"));
        var t = {
          trigger: "focus",
          template: '<div class="popover ' + a + '" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        };
        e.popover(t)
      }($(this))
    })
  }();

  var ScrollTo = function() {
    var e = $(".scroll-me, [data-scroll-to], .toc-entry a");

    function a(e) {
      var a = e.attr("href"),
        t = e.data("scroll-to-offset") ? e.data("scroll-to-offset") : 0,
        n = {
          scrollTop: $(a).offset().top - t
        };
      $("html, body").stop(!0, !0).animate(n, 600), event.preventDefault()
    }
    e.length && e.on("click", function(e) {
      a($(this))
    })
  }();

  var Tooltip = function() {
    var e = $('[data-toggle="tooltip"]');
    e.length && e.tooltip()
  }();

  var FormControl = function() {
    var e = $(".form-control");
    e.length && e.on("focus blur", function(e) {
      $(this).parents(".form-group").toggleClass("focused", "focus" === e.type)
    }).trigger("blur")
  }();

  var Checklist = function() {
    var e = $('[data-toggle="checklist"]');

    function a(e) {
      e.is(":checked") ? e.closest(".checklist-item").addClass("checklist-item-checked") : e.closest(".checklist-item").removeClass("checklist-item-checked")
    }
    e.length && (e.each(function() {
      $(this).find('.checklist-entry input[type="checkbox"]').each(function() {
        a($(this))
      })
    }), e.find('input[type="checkbox"]').on("change", function() {
      a($(this))
    }))
  }();

  var CopyIcon = function() {
    var e, a = ".btn-icon-clipboard",
      t = $(a);
    t.length && ((e = t).tooltip().on("mouseleave", function() {
      e.tooltip("hide")
    }), new ClipboardJS(a).on("success", function(e) {
      $(e.trigger).attr("title", "Copied!").tooltip("_fixTitle").tooltip("show").attr("title", "Copy to clipboard").tooltip("_fixTitle"), e.clearSelection()
    }))
  }();

  var Dropzones = function() {
    var e = $('[data-toggle="dropzone"]'),
      a = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function() {
      var e, t, n, i, o;
      e = $(this), t = void 0 !== e.data("dropzone-multiple"), n = e.find(a), i = void 0, o = {
        url: e.data("dropzone-url"),
        thumbnailWidth: null,
        thumbnailHeight: null,
        previewsContainer: n.get(0),
        previewTemplate: n.html(),
        maxFiles: t ? null : 1,
        acceptedFiles: t ? null : "image/*",
        init: function() {
          this.on("addedfile", function(e) {
            !t && i && this.removeFile(i), i = e
          })
        }
      }, n.html(""), e.dropzone(o)
    }))
  }();

  var noUiSlider = function() {
    if ($(".input-slider-container")[0] && $(".input-slider-container").each(function() {
        var e = $(this).find(".input-slider"),
          a = e.attr("id"),
          t = e.data("range-value-min"),
          n = e.data("range-value-max"),
          i = $(this).find(".range-slider-value"),
          o = i.attr("id"),
          s = i.data("range-value-low"),
          l = document.getElementById(a),
          r = document.getElementById(o);
        noUiSlider.create(l, {
          start: [parseInt(s)],
          connect: [!0, !1],
          range: {
            min: [parseInt(t)],
            max: [parseInt(n)]
          }
        }), l.noUiSlider.on("update", function(e, a) {
          r.textContent = e[a]
        })
      }), $("#input-slider-range")[0]) {
      var e = document.getElementById("input-slider-range"),
        a = document.getElementById("input-slider-range-value-low"),
        t = document.getElementById("input-slider-range-value-high"),
        n = [a, t];
      noUiSlider.create(e, {
        start: [parseInt(a.getAttribute("data-range-value-low")), parseInt(t.getAttribute("data-range-value-high"))],
        connect: !0,
        range: {
          min: parseInt(e.getAttribute("data-range-value-min")),
          max: parseInt(e.getAttribute("data-range-value-max"))
        }
      }), e.noUiSlider.on("update", function(e, a) {
        n[a].textContent = e[a]
      })
    }
  }();

  var Scrollbar = function() {
    var e = $(".scrollbar-inner");
    e.length && e.scrollbar().scrollLock()
  }();

  var SortList = function() {
    var e = $('[data-toggle="list"]'),
      a = $("[data-sort]");
    e.length && e.each(function() {
      var e;
      e = $(this), new List(e.get(0), function(e) {
        return {
          valueNames: e.data("list-values"),
          listClass: e.data("list-class") ? e.data("list-class") : "list"
        }
      }(e))
    }), a.on("click", function() {
      return !1
    })
  }();

  var OnScreen = function() {
    var e, a = $('[data-toggle="on-screen"]');
    a.length && (e = {
      container: window,
      direction: "vertical",
      doIn: function() {},
      doOut: function() {},
      tolerance: 200,
      throttle: 50,
      toggleClass: "on-screen",
      debug: !1
    }, a.onScreen(e))
  }();
});

/*
* ---------------------------------------
* Datatable
* ---------------------------------------
*/
var HandleDatatable = function() {
  var DatatableBasic = function() {
    var e = $("#datatable-basic");
    e.length && e.on("init.dt", function() {
      $("div.dataTables_length select").removeClass("custom-select custom-select-sm")
    }).DataTable({
      keys: !0,
      select: {
        style: "multi"
      },
      language: {
        paginate: {
          previous: "<i class='fas fa-angle-left'>",
          next: "<i class='fas fa-angle-right'>"
        }
      }
    })
  };

  var DatatableButtons = function() {
    var e, a = $("#datatable-buttons");
    a.length && (e = {
      lengthChange: !1,
      dom: "Bfrtip",
      buttons: ["copy", "print"],
      language: {
        paginate: {
          previous: "<i class='fas fa-angle-left'>",
          next: "<i class='fas fa-angle-right'>"
        }
      }
    }, a.on("init.dt", function() {
      $(".dt-buttons .btn").removeClass("btn-secondary").addClass("btn-sm btn-default")
    }).DataTable(e))
  };

  return {
      init: function() {
          DatatableBasic();
          DatatableButtons();
      }
  };
}();  

/*
* ---------------------------------------
* Datepicker
* ---------------------------------------
*/
var HandleDatepicker = function() {
  var Datepicker = function() {
    var e = $(".datepicker");
    e.length && e.each(function() {
      $(this).datepicker({
        format: 'yyyy-mm-dd',
        disableTouchKeyboard: !0,
        autoclose: !1
      })
    })
  };

  return {
      init: function() {
          Datepicker();
      }
  };
}();  

// =========================================================
// App General Function
// ---------------------------------------------------------
var App = function() {    
  return {        
    // wrapper function to scroll(focus) to an element
    scrollTo: function (el, offeset) {
        var pos = (el && el.length ) ? el.offset().top : 0;

        if (el) {
            if ($('body').hasClass('hold-transition')) {
                pos = pos - $('.navbar-top').height(); 
            }            
            pos = pos + (offeset ? offeset : -1 * el.height());
        }

        jQuery('html,body').animate({
            scrollTop: pos
        }, 'slow');
    },

    // function to scroll to the top
    scrollTop: function () {
        scrollTo();
    },
    
    getUniqueID: function(prefix) {
        return 'prefix_' + Math.floor(Math.random() * (new Date()).getTime());
    },
    
    notify: function(options) {
        options = $.extend(true, {
            icon: "", // put icon before the message
            title: "", // title notify
            message: "",  // message notify
            place: "top", // place in container 
            align: "center", // align in container 
            type: 'success',  // type notify
            enter: 'animated fadeInDown', // animate in
            exit: 'animated fadeOutUp', // animate in out
            timer: 2500, // delay close after defined seconds
        }, options);

        $.notify({
            icon: options.icon,
            title: options.title,
            message: options.message,
            url: ""
        }, {
            element: "body",
            type: options.type,
            allow_dismiss: !0,
            placement: {
                from: options.place,
               align: options.align
            },
            offset: {
                x: 15,
                y: 15
            },
            spacing: 10,
            z_index: 1080,
            delay: 2500,
            timer: options.timer,
            url_target: "_blank",
            mouse_over: !1,
            animate: {
                enter: options.enter,
                exit: options.exit
            },
            template: `<div data-notify="container" class="alert alert-dismissible alert-{0} alert-notify" role="alert">
                          <span class="alert-icon" data-notify="icon"></span> 
                          <div class="alert-text"> 
                            <span class="alert-title" data-notify="title">{1}</span> 
                            <span data-notify="message">{2}</span>
                          </div>
                          <button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      </div>`
        });
    },

    alert: function(options) {

        options = $.extend(true, {
            container: "", // alerts parent container(by default placed after the page breadcrumbs)
            place: "append", // append or prepent in container 
            type: 'success',  // alert's type
            message: "",  // alert's message
            close: true, // make alert closable
            reset: true, // close all previouse alerts first
            focus: true, // auto scroll to the alert after shown
            closeInSeconds: 0, // auto close after defined seconds
            icon: "" // put icon before the message
        }, options);

        var id = App.getUniqueID("app_alert");

        var html = '<div id="'+id+'" class="app-alerts alert alert-'+options.type+' fade show">' + 
          (options.close ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="fa fa-times"></i></button>' : '' ) + 
        (options.icon != "" ? '<i class="fa-lg fa fa-'+options.icon + ' mr-2"></i>  ' : '') + options.message+'</div>';

        if (options.reset) {
            $('.app-alerts').remove();
        }

        if (!options.container) {
            $('.page-breadcrumb').after(html);
        } else {
            if (options.place == "append") {
                $(options.container).append(html);
            } else {
                $(options.container).prepend(html);
            }
        }

        if (options.focus) {
            App.scrollTo($('#' + id));
        }

        if (options.closeInSeconds > 0) {
            setTimeout(function(){
                $('#' + id).remove();
            }, options.closeInSeconds * 1000);
        }
    },

    run_Loader: function(loader) {
        $('body').waitMe({
          effect: loader,
          text: 'Please wait...',
          bg: 'rgba(255,255,255,0.9)',
          color: '#000',
          maxSize: '',
          source: 'img.svg',
          onClose: function() {}
        });
    },

    close_Loader: function() {
        $('body').waitMe('hide');
    },
    
    kdName: function(update = '') {
        if ( update ) {
          $('.kd-content').data('name', update);
          return update;
        } else {
          return $('.kd-content').data('name');
        }
    },

    kdToken: function(update = '') {
        if ( update ) {
          $('.kd-content').data('token', update);
          return update;
        } else {
          return $('.kd-content').data('token');
        }
    },

    formatCurrency: function(currency, rp = false) {
        if (currency) {
            var number_string = currency.toString();
            sisa   = number_string.length % 3;
            rupiah = number_string.substr(0, sisa);
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah   += separator + ribuan.join('.');
            }
            return ( rp ? 'Rp ' : '' ) + rupiah;
        } else {
            return rp ? 'Rp 0 ' : '0';
        }
    },

    select2: function() {
        var e = $('[data-toggle="select2"]');
        e.length && e.each(function() {
          $(this).select2({})
        })
    },

    tags: function() {
        var e = $('[data-toggle="tags"]');
        e.length && e.each(function() {
          $(this).tagsinput({
            tagClass: "badge badge-primary"
          })
        })
    },

  };
}();