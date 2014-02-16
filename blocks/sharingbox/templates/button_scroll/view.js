!function ($) {

  $(function () {

    "use strict"; // jshint ;_;


    /* CSS TRANSITION SUPPORT (http://www.modernizr.com/)
     * ======================================================= */

    $.support.transition = (function () {

      var transitionEnd = (function () {

        var el = document.createElement('bootstrap')
          , transEndEventNames = {
               'WebkitTransition' : 'webkitTransitionEnd'
            ,  'MozTransition'    : 'transitionend'
            ,  'OTransition'      : 'oTransitionEnd otransitionend'
            ,  'transition'       : 'transitionend'
            }
          , name

        for (name in transEndEventNames){
          if (el.style[name] !== undefined) {
            return transEndEventNames[name]
          }
        }

      }())

      return transitionEnd && {
        end: transitionEnd
      }

    })()

  })

}(window.jQuery);

!function ($) {

  "use strict"; // jshint ;_;


 /* MODAL CLASS DEFINITION
  * ====================== */

  var Modal = function (element, options) {
    this.options = options
    this.$element = $(element)
      .delegate('[data-dismiss="modal"]', 'click.dismiss.modal', $.proxy(this.hide, this))
    this.options.remote && this.$element.find('.modal-body').load(this.options.remote)
  }

  Modal.prototype = {

      constructor: Modal

    , toggle: function () {
        return this[!this.isShown ? 'show' : 'hide']()
      }

    , show: function () {
        var that = this
          , e = $.Event('show')

        this.$element.trigger(e)

        if (this.isShown || e.isDefaultPrevented()) return

        $('body').addClass('modal-open')

        this.isShown = true

        this.escape()

        this.backdrop(function () {
          var transition = $.support.transition && that.$element.hasClass('fade')

          if (!that.$element.parent().length) {
            that.$element.appendTo(document.body) //don't move modals dom position
          }

          that.$element
            .show()

          if (transition) {
            that.$element[0].offsetWidth // force reflow
          }

          that.$element
            .addClass('in')
            .attr('aria-hidden', false)
            .focus()

          that.enforceFocus()

          transition ?
            that.$element.one($.support.transition.end, function () { that.$element.trigger('shown') }) :
            that.$element.trigger('shown')

        })
      }

    , hide: function (e) {
        e && e.preventDefault()

        var that = this

        e = $.Event('hide')

        this.$element.trigger(e)

        if (!this.isShown || e.isDefaultPrevented()) return

        this.isShown = false

        $('body').removeClass('modal-open')

        this.escape()

        $(document).off('focusin.modal')

        this.$element
          .removeClass('in')
          .attr('aria-hidden', true)

        $.support.transition && this.$element.hasClass('fade') ?
          this.hideWithTransition() :
          this.hideModal()
      }

    , enforceFocus: function () {
        var that = this
        $(document).on('focusin.modal', function (e) {
          if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
            that.$element.focus()
          }
        })
      }

    , escape: function () {
        var that = this
        if (this.isShown && this.options.keyboard) {
          this.$element.on('keyup.dismiss.modal', function ( e ) {
            e.which == 27 && that.hide()
          })
        } else if (!this.isShown) {
          this.$element.off('keyup.dismiss.modal')
        }
      }

    , hideWithTransition: function () {
        var that = this
          , timeout = setTimeout(function () {
              that.$element.off($.support.transition.end)
              that.hideModal()
            }, 500)

        this.$element.one($.support.transition.end, function () {
          clearTimeout(timeout)
          that.hideModal()
        })
      }

    , hideModal: function (that) {
        this.$element
          .hide()
          .trigger('hidden')

        this.backdrop()
      }

    , removeBackdrop: function () {
        this.$backdrop.remove()
        this.$backdrop = null
      }

    , backdrop: function (callback) {
        var that = this
          , animate = this.$element.hasClass('fade') ? 'fade' : ''

        if (this.isShown && this.options.backdrop) {
          var doAnimate = $.support.transition && animate

          this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')
            .appendTo(document.body)

          if (this.options.backdrop != 'static') {
            this.$backdrop.click($.proxy(this.hide, this))
          }

          if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

          this.$backdrop.addClass('in')

          doAnimate ?
            this.$backdrop.one($.support.transition.end, callback) :
            callback()

        } else if (!this.isShown && this.$backdrop) {
          this.$backdrop.removeClass('in')

          $.support.transition && this.$element.hasClass('fade')?
            this.$backdrop.one($.support.transition.end, $.proxy(this.removeBackdrop, this)) :
            this.removeBackdrop()

        } else if (callback) {
          callback()
        }
      }
  }


 /* MODAL PLUGIN DEFINITION
  * ======================= */

  $.fn.modal = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('modal')
        , options = $.extend({}, $.fn.modal.defaults, $this.data(), typeof option == 'object' && option)
      if (!data) $this.data('modal', (data = new Modal(this, options)))
      if (typeof option == 'string') data[option]()
      else if (options.show) data.show()
    })
  }

  $.fn.modal.defaults = {
      backdrop: true
    , keyboard: true
    , show: true
  }

  $.fn.modal.Constructor = Modal


 /* MODAL DATA-API
  * ============== */

  $(function () {
    $('body').on('click.modal.data-api', '[data-toggle="modal"]', function ( e ) {
      var $this = $(this)
        , href = $this.attr('href')
        , $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
        , option = $target.data('modal') ? 'toggle' : $.extend({ remote: !/#/.test(href) && href }, $target.data(), $this.data())

      e.preventDefault()

      $target
        .modal(option)
        .one('hide', function () {
          $this.focus()
        })
    })
  })

}(window.jQuery);

!function ($) {

  "use strict"; // jshint ;_;


 /* TOOLTIP PUBLIC CLASS DEFINITION
  * =============================== */

  var Tooltip = function (element, options) {
    this.init('tooltip', element, options)
  }

  Tooltip.prototype = {

    constructor: Tooltip

  , init: function (type, element, options) {
      var eventIn
        , eventOut

      this.type = type
      this.$element = $(element)
      this.options = this.getOptions(options)
      this.enabled = true

      if (this.options.trigger == 'click') {
        this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
      } else if (this.options.trigger != 'manual') {
        eventIn = this.options.trigger == 'hover' ? 'mouseenter' : 'focus'
        eventOut = this.options.trigger == 'hover' ? 'mouseleave' : 'blur'
        this.$element.on(eventIn + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
        this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
      }

      this.options.selector ?
        (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
        this.fixTitle()
    }

  , getOptions: function (options) {
      options = $.extend({}, $.fn[this.type].defaults, options, this.$element.data())

      if (options.delay && typeof options.delay == 'number') {
        options.delay = {
          show: options.delay
        , hide: options.delay
        }
      }

      return options
    }

  , enter: function (e) {
      var self = $(e.currentTarget)[this.type](this._options).data(this.type)

      if (!self.options.delay || !self.options.delay.show) return self.show()

      clearTimeout(this.timeout)
      self.hoverState = 'in'
      this.timeout = setTimeout(function() {
        if (self.hoverState == 'in') self.show()
      }, self.options.delay.show)
    }

  , leave: function (e) {
      var self = $(e.currentTarget)[this.type](this._options).data(this.type)

      if (this.timeout) clearTimeout(this.timeout)
      if (!self.options.delay || !self.options.delay.hide) return self.hide()

      self.hoverState = 'out'
      this.timeout = setTimeout(function() {
        if (self.hoverState == 'out') self.hide()
      }, self.options.delay.hide)
    }

  , show: function () {
      var $tip
        , inside
        , pos
        , actualWidth
        , actualHeight
        , placement
        , tp

      if (this.hasContent() && this.enabled) {
        $tip = this.tip()
        this.setContent()

        if (this.options.animation) {
          $tip.addClass('fade')
        }

        placement = typeof this.options.placement == 'function' ?
          this.options.placement.call(this, $tip[0], this.$element[0]) :
          this.options.placement

        inside = /in/.test(placement)

        $tip
          .remove()
          .css({ top: 0, left: 0, display: 'block' })
          .appendTo(inside ? this.$element : document.body)

        pos = this.getPosition(inside)

        actualWidth = $tip[0].offsetWidth
        actualHeight = $tip[0].offsetHeight

        switch (inside ? placement.split(' ')[1] : placement) {
          case 'bottom':
            tp = {top: pos.top + pos.height, left: pos.left + pos.width / 2 - actualWidth / 2}
            break
          case 'top':
            tp = {top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2}
            break
          case 'left':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth}
            break
          case 'right':
            tp = {top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width}
            break
        }

        $tip
          .css(tp)
          .addClass(placement)
          .addClass('in')
      }
    }

  , setContent: function () {
      var $tip = this.tip()
        , title = this.getTitle()

      $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
      $tip.removeClass('fade in top bottom left right')
    }

  , hide: function () {
      var that = this
        , $tip = this.tip()

      $tip.removeClass('in')

      function removeWithAnimation() {
        var timeout = setTimeout(function () {
          $tip.off($.support.transition.end).remove()
        }, 500)

        $tip.one($.support.transition.end, function () {
          clearTimeout(timeout)
          $tip.remove()
        })
      }

      $.support.transition && this.$tip.hasClass('fade') ?
        removeWithAnimation() :
        $tip.remove()

      return this
    }

  , fixTitle: function () {
      var $e = this.$element
      if ($e.attr('title') || typeof($e.attr('data-original-title')) != 'string') {
        $e.attr('data-original-title', $e.attr('title') || '').removeAttr('title')
      }
    }

  , hasContent: function () {
      return this.getTitle()
    }

  , getPosition: function (inside) {
      return $.extend({}, (inside ? {top: 0, left: 0} : this.$element.offset()), {
        width: this.$element[0].offsetWidth
      , height: this.$element[0].offsetHeight
      })
    }

  , getTitle: function () {
      var title
        , $e = this.$element
        , o = this.options

      title = $e.attr('data-original-title')
        || (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

      return title
    }

  , tip: function () {
      return this.$tip = this.$tip || $(this.options.template)
    }

  , validate: function () {
      if (!this.$element[0].parentNode) {
        this.hide()
        this.$element = null
        this.options = null
      }
    }

  , enable: function () {
      this.enabled = true
    }

  , disable: function () {
      this.enabled = false
    }

  , toggleEnabled: function () {
      this.enabled = !this.enabled
    }

  , toggle: function () {
      this[this.tip().hasClass('in') ? 'hide' : 'show']()
    }

  , destroy: function () {
      this.hide().$element.off('.' + this.type).removeData(this.type)
    }

  }


 /* TOOLTIP PLUGIN DEFINITION
  * ========================= */

  $.fn.tooltip = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('tooltip')
        , options = typeof option == 'object' && option
      if (!data) $this.data('tooltip', (data = new Tooltip(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.tooltip.Constructor = Tooltip

  $.fn.tooltip.defaults = {
    animation: true
  , placement: 'top'
  , selector: false
  , template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
  , trigger: 'hover'
  , title: ''
  , delay: 0
  , html: true
  }

}(window.jQuery);

!function ($) {

  "use strict"; // jshint ;_;


 /* BUTTON PUBLIC CLASS DEFINITION
  * ============================== */

  var Button = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, $.fn.button.defaults, options)
  }

  Button.prototype.setState = function (state) {
    var d = 'disabled'
      , $el = this.$element
      , data = $el.data()
      , val = $el.is('input') ? 'val' : 'html'

    state = state + 'Text'
    data.resetText || $el.data('resetText', $el[val]())

    $el[val](data[state] || this.options[state])

    // push to event loop to allow forms to submit
    setTimeout(function () {
      state == 'loadingText' ?
        $el.addClass(d).attr(d, d) :
        $el.removeClass(d).removeAttr(d)
    }, 0)
  }

  Button.prototype.toggle = function () {
    var $parent = this.$element.closest('[data-toggle="buttons-radio"]')

    $parent && $parent
      .find('.active')
      .removeClass('active')

    this.$element.toggleClass('active')
  }


 /* BUTTON PLUGIN DEFINITION
  * ======================== */

  $.fn.button = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('button')
        , options = typeof option == 'object' && option
      if (!data) $this.data('button', (data = new Button(this, options)))
      if (option == 'toggle') data.toggle()
      else if (option) data.setState(option)
    })
  }

  $.fn.button.defaults = {
    loadingText: 'loading...'
  }

  $.fn.button.Constructor = Button


 /* BUTTON DATA-API
  * =============== */

  $(function () {
    $('body').on('click.button.data-api', '[data-toggle^=button]', function ( e ) {
      var $btn = $(e.target)
      if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn')
      $btn.button('toggle')
    })
  })

}(window.jQuery);



cws_filesUploadedDialog = function(searchInstance) {
  $('.modal-backdrop').hide();
  if(document.getElementById('ccm-file-upload-multiple-tab'))
    jQuery.fn.dialog.closeTop()
  var fIDstring='';
  for( var i=0; i< ccm_uploadedFiles.length; i++ )
    fIDstring=fIDstring+'&fID[]='+ccm_uploadedFiles[i];
  jQuery.fn.dialog.open({
    width: 690,
    height: 440,
    modal: false,
    href: GBX_COMPLETED_TOOL + '/?'+fIDstring + '&uploaded=true&searchInstance=' + searchInstance,
    onClose: function() {
      cwsuploadComplete();
    },
    title: ccmi18n_filemanager.uploadComplete
  });
  ccm_uploadedFiles=[];
}

cwsCloseModal = function(){
  $("#cwsPhotoUploadModal").hide();
}

cwsuploadComplete = function() {
  $(function() {
    $.post(SB_UPLOAD_COMPLETE, {ajax:1}).done(function(data) {
      $('.commentable_sharingbox_wall').before(data).remove();

    });

    return false;
  });
}

function readyCommentStream(){

  //initialize element states
  $("#cws-status a").css('cursor', 'pointer');
  $("#cws-link a").css('cursor', 'pointer');
  $("#cws-photo a").css('cursor', 'pointer');
  $("#action").val('status_share');
  $("#cws-everyone, .cws-edit-post, .cws-delete-post, .cws-delete-comment, .cws-edit-comment, #cws-friends, .cws-friends-edit, .cws-everyone-edit, .post-cancel , .post-edit, .shared-friends, .shared-everyone, .cws-post-update-btn, .cws-comment-update-btn, .comment-cancel").tooltip();
  $("#cws-everyone").show();
  $("#cws-friends, #cws-friends-edit").hide();
  $("#cws-upload").hide();
  $("#statlinkcomment-wrap").hide();
  $("#sw").val( '2' );

  $("#statext, #statlinkcomment").focus(function () {
    if($(this).val() == linkCommentPlaceholder || $(this).val() == sharedTextPlaceholder){
      $(this).val('')
    }
    $(this).css('color', '#000000');
  });

  $("#cws-status a").click(function () {
    $("#statext").val(sharedTextPlaceholder).css('color', '#999999').show();
    $("#action").val('status_share');
    $("#form-button").show();
    $("#statlinkcomment-wrap").hide();
  });

  $("#cws-link a").click(function () {
    $("#statext").val('http://').show();
    $("#statext, #statlinkcomment").css('color', '#999999');
    $("#action").val('link_share');
    $("#form-button").show();
    $("#statlinkcomment").val(linkCommentPlaceholder);
    $("#statlinkcomment-wrap").show();
  });

  $("#cws-photo a").click(function () {
    $("#statext").val(sharedTextPlaceholder).css('color', '#999999');
    $("#statlinkcomment-wrap").hide();
    $('#cwsPhotoUploadModal').show();
  });

  $("#cws-everyone").click(function () {
    $(this).hide();
    $("#sw").val( '1' );
    $("#cws-friends").show();
  });

  $("#cws-friends").click(function () {
    $(this).hide();
    $("#sw").val( '2' );
    $("#cws-everyone").show();
  });

  $(".cws-everyone-edit").click(function () {
    var pID = $(this).attr("id").match(/[\d]+$/);
    $(this).hide();
    $("#sw-edit_" + pID).val( '1' );
    $("#cws-friends_" + pID).show();
  });

  $(".cws-friends-edit").click(function () {
    var pID = $(this).attr("id").match(/[\d]+$/);
    $(this).hide();
    $("#sw-edit_" + pID).val( '2' );
    $("#cws-everyone_" + pID).show();
  });

  $('.delete-post-btn').click(function() {
    $(".tooltip").hide();
    var pID = $(this).attr("id").match(/[\d]+$/);
    console.log("derp");
    $.post(SB_POST_DELETE, {
      pID       : pID,
      sbUID     : sbUID,
      ccm_token : ccm_token,
      ajax      : 1
    }).done(function() {
      $('.modal-backdrop, .modal').hide();
      $("#cws-item-class_" + pID).remove();
    });

    return false;
  });

  $('.cws-comment-update-btn').click(function() {
    $(".tooltip").hide();
    var commID = $(this).attr("id").match(/[\d]+$/);
    var comtext = $("input#comment-edit_" + commID).val();
    var pID = $("input#commpID_" + commID).val();

    $.post(SB_UPDATE_COMMENT, {
      pID       : pID,
      commID    : commID,
      comtext   : comtext,
      sbUID     : sbUID,
      ccm_token : ccm_token,
      ajax      : 1
    }).done(function(data) {
      $('div.cws-comments_' + pID).before(data).remove();
      $('div.cws-comments_' + pID).effect("highlight", {}, 800);
    });

    return false;
  });

  $(".commButtonToggle").click(function() {
    var commID = $(this).attr('data-commid');
    $(".delete-comment-btn").attr("id", "commentDelete_" + commID);
  });

  $('.cws-edit-post').click(function() {
    $(".tooltip").hide();
    var pID = $(this).attr("id").match(/[\d]+$/);
    var statusText = $(".cws-status-post", "#posting_" + pID).text();
    var commentText = $(".cws-wall-link-comment", "#posting_" + pID).text();
    var statLinkParent = $(".cws-wall-link", "#posting_" + pID);
    var embedLink = $(".hidden-link-edit", statLinkParent).val();
    var statLink = $("a", statLinkParent).attr("href");
    if (statLink == null && embedLink == null){
      $("#statext-edit_" + pID).val(statusText);
    }else{
      $("#statext-edit_" + pID).val(commentText);
      if(embedLink == null){
        $("#statlink-edit_" + pID).val(statLink);
      }else{
        $("#statlink-edit_" + pID).val(embedLink);
      }
    }
    $(".cws-posting").show();
    $(".editPosting").hide();

    $("#posting_" + pID).hide();
    $("#editPosting_" + pID).show();
  });

  $('.cws-edit-comment').click(function() {
    $(".tooltip").hide();
    var commID = $(this).attr("id").match(/[\d]+$/);
    var pID = $("input#commpID_" + commID).val();
    $(".cws-wall-post-comment").show();
    $(".editComment").hide();
    $("#cws-comment-form_" + pID).hide();
    $("#cws-wall-post-comment_" + commID).hide();
    $("#editComment_" + commID).show();
  });

  $('.comment-cancel').click(function() {
    $(".tooltip").hide();
    $(".cws-wall-post-comment").show();
    $(".editComment").hide();
    $(".cws-comment-form").show();
  });

  $('.post-cancel').click(function() {
    $('.tooltip, .editPosting').hide();
    $(".cws-posting").show();
  });


  $('.delete-comment-btn').click(function() {
    $(".tooltip").hide();
    var commID = $(this).attr("id").match(/[\d]+$/);
    $.post(SB_COMMENT_DELETE, {
      commID    : commID,
      sbUID     : sbUID,
      ccm_token : ccm_token,
      ajax      : 1
    }).done(function() {
      $('.modal-backdrop, .modal').hide();
      $("#cws-wall-post-comment_" + commID).remove();
    });

    return false;
  });

  $('.cws-post-update-btn').on('click', function() {
    $(".tooltip").hide();
    var pID     = $(this).data("id");
    var pType   = $("input#pType_" + pID).val();
    var sw      = $("input#sw-edit_" + pID).val();
    var offset  = 10;
    var statext = "";

    if(pType == 'sb_link'){
      statext = $("input#statlink-edit_" + pID).val();
      var statlinkcomment = $("input#statext-edit_" + pID).val();
    }else{
      statext = $("input#statext-edit_" + pID).val();
    }
     $.post(SB_POST_UPDATE, {
      pID             : pID,
      pType           : pType,
      statext         : statext,
      statlinkcomment : statlinkcomment,
      sw              : sw,
      sbUID           : sbUID,
      ccm_token       : ccm_token,
      ajax            : 1
     }).done(function(data) {
        $('.commentable_sharingbox_wall').before(data).remove();
      });

    return false;
  });

   $(".cwsComment").focus(function () {
    $(this).val( '' );
    $(this).css( 'color', '#000000' );
    });

  $('.cws-comment-bar').click(function() {
    var pID = $(this).attr("id").match(/[\d]+$/);
    $("#cwsComment_" + pID).focus();
  });

  $(".cws-wall-post-comment, .commentable-wall-item").hover(
    function () {
        $('li.cws-edit-tools',this).show();
      },
      function () {
      $('li.cws-edit-tools',this).hide();
   });

  $("#photo-upload-btn").click(function() {
    $(this).button('loading');
  });
}

function postComment(pID){
  $.post(SB_COMMENT_HELPER, {
    pID       : pID,
    comtext   : $("input#cwsComment_" + pID).val(),
    sbUID     : sbUID,
    ccm_token : ccm_token,
    ajax      : 1
  }).done(function(data) {
    $('div.cws-comments_' + pID).before(data).remove();
    $('div.cws-comments_' + pID).effect("highlight", {}, 800);
  });

  return false;
}

$(document).ready(function() {

  $("#more-posts-loader-button").on('click', function(e){
    $("#more-posts-loader").show();
    offset += 10;

    $.post(SB_POST_LOADER, {
      offset    : offset,
      sbUID     : sbUID,
      ccm_token : ccm_token,
      ajax      : 1
    }).done(function(data) {
      $('#more-posts').append(data);
      $("#more-posts-loader").hide();
    });
    return false;
  });


  $("#cws-status-form").on('submit', function() {
    $(".loading").show();
    $("#statlinkcomment-wrap, .tooltip").hide();

    var action          = $("input#action").val();
    var statlinkcomment = $("input#statlinkcomment").val();
    var sw              = $("input#sw").val();
    var statext         = $("input#statext").val();

    $.post(SB_TOOLS_DIR + action, {
      statext         : statext,
      statlinkcomment : statlinkcomment,
      sw              : sw,
      sbUID           : sbUID,
      ccm_token       : ccm_token,
      ajax            : 1
    }).done(function(data) {
      $('#sb-wall').before(data).remove();
    });

    $("input#statext").blur().val(sharedTextPlaceholder).css('color', '#999999');

    return false;
  });

});
