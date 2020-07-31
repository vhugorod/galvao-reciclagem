(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="znhgtfw-modal-wrapper">\r\n\t<div class="znhgtfw-modal-header">\r\n\t\t<div class="znhgtfw-modal-title">\r\n\t\t\tShortcodes\r\n\t\t</div>\r\n\t\t<button type="button" class="button-link media-modal-close">\r\n\t\t\t<span class="media-modal-icon">\r\n\t\t\t\t<span class="screen-reader-text">Close media panel</span>\r\n\t\t\t</span>\r\n\t\t</button>\r\n\t</div>\r\n\t<div class="znhgtfw-modal-content-wrapper">\r\n\t\t<div class="znhgtfw-modal-sidebar"></div>\r\n\t\t<div class="znhgtfw-modal-content">\r\n\t\t\t<div class="znhgtfw-shortcode-mngr-nothing-selected">\r\n\t\t\t\t<p>Choose a shortcode from the sidebar to get started.</p>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n\t<div class="znhgtfw-modal-footer">\r\n\t\t<div class="znhgtfw-footer-nav">\r\n\t\t\t<a href="#" class="znhgtfw-button znhg-shortcode-insert">Insert shortcode</a>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n<div class="znhgtfw-modal-backdrop"></div>';
}
return __p;
};

},{}],2:[function(require,module,exports){
module.exports = Backbone.Model.extend({
	defaults : {
		id : 'shortcode-tag',
		name : 'Shortcode Name',
		section : 'Section',
		description : 'Shortcode description',
		params : [],
	},
	setSelected:function() {
		this.collection.setSelected(this);
	}
});
},{}],3:[function(require,module,exports){
var ShortcodesCollection = Backbone.Collection.extend({
	model: require('./shortcodeModel'),
	initialize: function() {
		this.selected = null;
	},
	bySection : function(sectionName){
		filtered = this.filter(function ( shortcode ) {
			return shortcode.get('section') === sectionName;
		});
		return new ShortcodesCollection(filtered);
	},
	setSelected: function( shortcode ) {
		if (this.selected) {
			this.selected.set({selected:false});
		}
		shortcode.set({selected:true});
		this.selected = shortcode;
		this.trigger('shortcodeSelected', shortcode);
	},
	getSelected : function(){
		return this.selected;
	}
});

module.exports = ShortcodesCollection;
},{"./shortcodeModel":2}],4:[function(require,module,exports){
window.znhg = window.znhg || {};
if( typeof(window.ZnHgShManager) =='undefined') {
	throw new Error('Error: ZnHgShManager was not found.');
}
znhgShortcodesManagerData = {};

znhgShortcodesManagerData.sections = ZnHgShManager.sections;
znhgShortcodesManagerData.shortcodes = ZnHgShManager.shortcodes;

(function ($) {
	var App = function(){},
		ModalView = require('./views/modal'),
		ShortcodesCollection = require('./models/shortcodesCollection');

	/**
	 * Starts the main shortcode manager class
	 */
	App.prototype.start = function(){
		// Bind the click event
		$(document).on('click', '#znhgtfw-shortcode-modal-open', function(e){
			e.preventDefault();
			this.openModal();
		}.bind(this));

		this.shortcodesCollection = new ShortcodesCollection(znhgShortcodesManagerData.shortcodes);

		// Allow chaining
		return this;
	};

	/**
	 * Opens the modal window
	 */
	App.prototype.openModal = function(){
		// Only allow an instance of the modalView
		if( this.modalView === undefined ){
			this.modalView = new ModalView({collection: this.shortcodesCollection, app : this});
		}
	};

	/**
	 * Opens the modal window
	 */
	App.prototype.closeModal = function(){
		this.modalView = undefined;
	};

	znhg.shortcodesManager = new App().start();

})(jQuery);

},{"./models/shortcodesCollection":3,"./views/modal":5}],5:[function(require,module,exports){
var navView = require('./navView');

module.exports = Backbone.View.extend({
	id: "znhgtfw-shortcodes-modal",
	template : require('../html/modal.html'),
	events : {
		'click .znhgtfw-modal-backdrop': 'modalClose',
		'click .media-modal-close':      'modalClose',
		'click .znhg-shortcode-insert':  'insertShortcode'
	},
	initialize : function( options ){
		this.mainApp = options.app;
		this.listenTo(this.collection, 'shortcodeSelected', this.renderParams);
		this.render();
	},
	render : function(){
		this.$el.html( this.template() );

		// Add the navigation
		this.$('.znhgtfw-modal-sidebar').append( new navView().render().$el );

		// Finally.. add the modal to the page
		jQuery( 'body' ).append( this.$el ).addClass('znhgtfw-modal-open');

		return this;
	},
	modalClose : function(){
		this.$el.remove();
		jQuery('body').removeClass('znhgtfw-modal-open');
		this.mainApp.closeModal();
		this.remove();
	},
	renderParams: function( shortcode ){
		// We will need to render the form
		this.paramsCollection = znhg.optionsMachine.setupParams( shortcode.get('params') );
		var form = znhg.optionsMachine.renderOptionsGroup(this.paramsCollection);
		this.$('.znhgtfw-modal-content').html(form);
	},
	insertShortcode : function(shortcode){

		var shortcodeTag    = this.collection.selected.get( 'id' ),
			changedParams   = this.paramsCollection.where({ isChanged: true }),
			closeShortcode  = this.collection.selected.get( 'hasContent' ) || false,
			shortcodeContent = this.collection.selected.get( 'defaultContent' ) || false,
			output;

		// Open the shortcode tag
		output = '['+ shortcodeTag;
			// output all the shortcode params/attributes
			_.each(changedParams, function(param){
				// Don't add the content attribute
				if( param.get('id') === 'content' ){
					// Set the closeShortcode to true
					closeShortcode = true;
					shortcodeContent = param.get('value');
					return true;
				}
				// Output the param_name=param_value
				output += ' '+ param.get('id') + '="' + param.get('value') +'"';
			});
		output += ']';

		// If we have content, add the content and also add the closing tag
		if ( shortcodeContent ) {
			output += shortcodeContent;
		}

		// Check if we need to close the shortcode
		if( closeShortcode ){
			output += '[/' + shortcodeTag + ']';
		}

		window.wp.media.editor.insert( output );
		this.modalClose();
	}
});
},{"../html/modal.html":1,"./navView":8}],6:[function(require,module,exports){
module.exports = Backbone.View.extend({
	tagName : 'li',
	events : {
		'click' : 'selectShortcode'
	},
	render : function(){
		this.$el.html( jQuery('<a href="#">' + this.model.get('name') + '</a>') );
		return this;
	},
	selectShortcode : function(){
		this.model.setSelected();
	}
});
},{}],7:[function(require,module,exports){
var navItem = require('./navItem');
module.exports = Backbone.View.extend({
	tagName: 'ul',
	className : 'znhgtfw-modal-menu-dropdown',
	render : function(){
		this.collection.each(function( shortcode ){
			this.$el.append(new navItem({model: shortcode}).render().$el);
		}.bind(this));
		return this;
	}
});
},{"./navItem":6}],8:[function(require,module,exports){
var navSection = require('./navSection');
module.exports = Backbone.View.extend({
	tagName: 'ul',
	className : 'znhgtfw-modal-menu',
	events : {
		'click > li > a' : 'toggleSection'
	},
	render : function(){
		_(znhgShortcodesManagerData.sections).each(function(sectionName){
			var $li = jQuery('<li></li>');
			$li.append('<a href="#">'+ sectionName +'</a>');
			$li.append( new navSection( { collection: znhg.shortcodesManager.shortcodesCollection.bySection( sectionName ) } ).render().$el );
			this.$el.append($li);
		}.bind(this));
		return this;
	},
	toggleSection : function(e){
		this.$el.find('li').removeClass('active');
		jQuery(e.target).parent().addClass('active');
	}
});
},{"./navSection":7}]},{},[4])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIi4uL25vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJhc3NldHMvc3JjL2pzL2FkbWluL3Nob3J0Y29kZXMvaHRtbC9tb2RhbC5odG1sIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9zaG9ydGNvZGVzL21vZGVscy9zaG9ydGNvZGVNb2RlbC5qcyIsImFzc2V0cy9zcmMvanMvYWRtaW4vc2hvcnRjb2Rlcy9tb2RlbHMvc2hvcnRjb2Rlc0NvbGxlY3Rpb24uanMiLCJhc3NldHMvc3JjL2pzL2FkbWluL3Nob3J0Y29kZXMvc2hvcnRjb2Rlcy5qcyIsImFzc2V0cy9zcmMvanMvYWRtaW4vc2hvcnRjb2Rlcy92aWV3cy9tb2RhbC5qcyIsImFzc2V0cy9zcmMvanMvYWRtaW4vc2hvcnRjb2Rlcy92aWV3cy9uYXZJdGVtLmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9zaG9ydGNvZGVzL3ZpZXdzL25hdlNlY3Rpb24uanMiLCJhc3NldHMvc3JjL2pzL2FkbWluL3Nob3J0Y29kZXMvdmlld3MvbmF2Vmlldy5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTtBQ0FBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDUEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ1hBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ3hCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDbERBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQzNFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNaQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ1ZBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSIsImZpbGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCJtb2R1bGUuZXhwb3J0cyA9IGZ1bmN0aW9uKG9iail7XG52YXIgX190LF9fcD0nJyxfX2o9QXJyYXkucHJvdG90eXBlLmpvaW4scHJpbnQ9ZnVuY3Rpb24oKXtfX3ArPV9fai5jYWxsKGFyZ3VtZW50cywnJyk7fTtcbndpdGgob2JqfHx7fSl7XG5fX3ArPSc8ZGl2IGNsYXNzPVwiem5oZ3Rmdy1tb2RhbC13cmFwcGVyXCI+XFxyXFxuXFx0PGRpdiBjbGFzcz1cInpuaGd0ZnctbW9kYWwtaGVhZGVyXCI+XFxyXFxuXFx0XFx0PGRpdiBjbGFzcz1cInpuaGd0ZnctbW9kYWwtdGl0bGVcIj5cXHJcXG5cXHRcXHRcXHRTaG9ydGNvZGVzXFxyXFxuXFx0XFx0PC9kaXY+XFxyXFxuXFx0XFx0PGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJidXR0b24tbGluayBtZWRpYS1tb2RhbC1jbG9zZVwiPlxcclxcblxcdFxcdFxcdDxzcGFuIGNsYXNzPVwibWVkaWEtbW9kYWwtaWNvblwiPlxcclxcblxcdFxcdFxcdFxcdDxzcGFuIGNsYXNzPVwic2NyZWVuLXJlYWRlci10ZXh0XCI+Q2xvc2UgbWVkaWEgcGFuZWw8L3NwYW4+XFxyXFxuXFx0XFx0XFx0PC9zcGFuPlxcclxcblxcdFxcdDwvYnV0dG9uPlxcclxcblxcdDwvZGl2PlxcclxcblxcdDxkaXYgY2xhc3M9XCJ6bmhndGZ3LW1vZGFsLWNvbnRlbnQtd3JhcHBlclwiPlxcclxcblxcdFxcdDxkaXYgY2xhc3M9XCJ6bmhndGZ3LW1vZGFsLXNpZGViYXJcIj48L2Rpdj5cXHJcXG5cXHRcXHQ8ZGl2IGNsYXNzPVwiem5oZ3Rmdy1tb2RhbC1jb250ZW50XCI+XFxyXFxuXFx0XFx0XFx0PGRpdiBjbGFzcz1cInpuaGd0Znctc2hvcnRjb2RlLW1uZ3Itbm90aGluZy1zZWxlY3RlZFwiPlxcclxcblxcdFxcdFxcdFxcdDxwPkNob29zZSBhIHNob3J0Y29kZSBmcm9tIHRoZSBzaWRlYmFyIHRvIGdldCBzdGFydGVkLjwvcD5cXHJcXG5cXHRcXHRcXHQ8L2Rpdj5cXHJcXG5cXHRcXHQ8L2Rpdj5cXHJcXG5cXHQ8L2Rpdj5cXHJcXG5cXHQ8ZGl2IGNsYXNzPVwiem5oZ3Rmdy1tb2RhbC1mb290ZXJcIj5cXHJcXG5cXHRcXHQ8ZGl2IGNsYXNzPVwiem5oZ3Rmdy1mb290ZXItbmF2XCI+XFxyXFxuXFx0XFx0XFx0PGEgaHJlZj1cIiNcIiBjbGFzcz1cInpuaGd0ZnctYnV0dG9uIHpuaGctc2hvcnRjb2RlLWluc2VydFwiPkluc2VydCBzaG9ydGNvZGU8L2E+XFxyXFxuXFx0XFx0PC9kaXY+XFxyXFxuXFx0PC9kaXY+XFxyXFxuPC9kaXY+XFxyXFxuPGRpdiBjbGFzcz1cInpuaGd0ZnctbW9kYWwtYmFja2Ryb3BcIj48L2Rpdj4nO1xufVxucmV0dXJuIF9fcDtcbn07XG4iLCJtb2R1bGUuZXhwb3J0cyA9IEJhY2tib25lLk1vZGVsLmV4dGVuZCh7XHJcblx0ZGVmYXVsdHMgOiB7XHJcblx0XHRpZCA6ICdzaG9ydGNvZGUtdGFnJyxcclxuXHRcdG5hbWUgOiAnU2hvcnRjb2RlIE5hbWUnLFxyXG5cdFx0c2VjdGlvbiA6ICdTZWN0aW9uJyxcclxuXHRcdGRlc2NyaXB0aW9uIDogJ1Nob3J0Y29kZSBkZXNjcmlwdGlvbicsXHJcblx0XHRwYXJhbXMgOiBbXSxcclxuXHR9LFxyXG5cdHNldFNlbGVjdGVkOmZ1bmN0aW9uKCkge1xyXG5cdFx0dGhpcy5jb2xsZWN0aW9uLnNldFNlbGVjdGVkKHRoaXMpO1xyXG5cdH1cclxufSk7IiwidmFyIFNob3J0Y29kZXNDb2xsZWN0aW9uID0gQmFja2JvbmUuQ29sbGVjdGlvbi5leHRlbmQoe1xyXG5cdG1vZGVsOiByZXF1aXJlKCcuL3Nob3J0Y29kZU1vZGVsJyksXHJcblx0aW5pdGlhbGl6ZTogZnVuY3Rpb24oKSB7XHJcblx0XHR0aGlzLnNlbGVjdGVkID0gbnVsbDtcclxuXHR9LFxyXG5cdGJ5U2VjdGlvbiA6IGZ1bmN0aW9uKHNlY3Rpb25OYW1lKXtcclxuXHRcdGZpbHRlcmVkID0gdGhpcy5maWx0ZXIoZnVuY3Rpb24gKCBzaG9ydGNvZGUgKSB7XHJcblx0XHRcdHJldHVybiBzaG9ydGNvZGUuZ2V0KCdzZWN0aW9uJykgPT09IHNlY3Rpb25OYW1lO1xyXG5cdFx0fSk7XHJcblx0XHRyZXR1cm4gbmV3IFNob3J0Y29kZXNDb2xsZWN0aW9uKGZpbHRlcmVkKTtcclxuXHR9LFxyXG5cdHNldFNlbGVjdGVkOiBmdW5jdGlvbiggc2hvcnRjb2RlICkge1xyXG5cdFx0aWYgKHRoaXMuc2VsZWN0ZWQpIHtcclxuXHRcdFx0dGhpcy5zZWxlY3RlZC5zZXQoe3NlbGVjdGVkOmZhbHNlfSk7XHJcblx0XHR9XHJcblx0XHRzaG9ydGNvZGUuc2V0KHtzZWxlY3RlZDp0cnVlfSk7XHJcblx0XHR0aGlzLnNlbGVjdGVkID0gc2hvcnRjb2RlO1xyXG5cdFx0dGhpcy50cmlnZ2VyKCdzaG9ydGNvZGVTZWxlY3RlZCcsIHNob3J0Y29kZSk7XHJcblx0fSxcclxuXHRnZXRTZWxlY3RlZCA6IGZ1bmN0aW9uKCl7XHJcblx0XHRyZXR1cm4gdGhpcy5zZWxlY3RlZDtcclxuXHR9XHJcbn0pO1xyXG5cclxubW9kdWxlLmV4cG9ydHMgPSBTaG9ydGNvZGVzQ29sbGVjdGlvbjsiLCJ3aW5kb3cuem5oZyA9IHdpbmRvdy56bmhnIHx8IHt9O1xyXG5pZiggdHlwZW9mKHdpbmRvdy5abkhnU2hNYW5hZ2VyKSA9PSd1bmRlZmluZWQnKSB7XHJcblx0dGhyb3cgbmV3IEVycm9yKCdFcnJvcjogWm5IZ1NoTWFuYWdlciB3YXMgbm90IGZvdW5kLicpO1xyXG59XHJcbnpuaGdTaG9ydGNvZGVzTWFuYWdlckRhdGEgPSB7fTtcclxuXHJcbnpuaGdTaG9ydGNvZGVzTWFuYWdlckRhdGEuc2VjdGlvbnMgPSBabkhnU2hNYW5hZ2VyLnNlY3Rpb25zO1xyXG56bmhnU2hvcnRjb2Rlc01hbmFnZXJEYXRhLnNob3J0Y29kZXMgPSBabkhnU2hNYW5hZ2VyLnNob3J0Y29kZXM7XHJcblxyXG4oZnVuY3Rpb24gKCQpIHtcclxuXHR2YXIgQXBwID0gZnVuY3Rpb24oKXt9LFxyXG5cdFx0TW9kYWxWaWV3ID0gcmVxdWlyZSgnLi92aWV3cy9tb2RhbCcpLFxyXG5cdFx0U2hvcnRjb2Rlc0NvbGxlY3Rpb24gPSByZXF1aXJlKCcuL21vZGVscy9zaG9ydGNvZGVzQ29sbGVjdGlvbicpO1xyXG5cclxuXHQvKipcclxuXHQgKiBTdGFydHMgdGhlIG1haW4gc2hvcnRjb2RlIG1hbmFnZXIgY2xhc3NcclxuXHQgKi9cclxuXHRBcHAucHJvdG90eXBlLnN0YXJ0ID0gZnVuY3Rpb24oKXtcclxuXHRcdC8vIEJpbmQgdGhlIGNsaWNrIGV2ZW50XHJcblx0XHQkKGRvY3VtZW50KS5vbignY2xpY2snLCAnI3puaGd0Znctc2hvcnRjb2RlLW1vZGFsLW9wZW4nLCBmdW5jdGlvbihlKXtcclxuXHRcdFx0ZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cdFx0XHR0aGlzLm9wZW5Nb2RhbCgpO1xyXG5cdFx0fS5iaW5kKHRoaXMpKTtcclxuXHJcblx0XHR0aGlzLnNob3J0Y29kZXNDb2xsZWN0aW9uID0gbmV3IFNob3J0Y29kZXNDb2xsZWN0aW9uKHpuaGdTaG9ydGNvZGVzTWFuYWdlckRhdGEuc2hvcnRjb2Rlcyk7XHJcblxyXG5cdFx0Ly8gQWxsb3cgY2hhaW5pbmdcclxuXHRcdHJldHVybiB0aGlzO1xyXG5cdH07XHJcblxyXG5cdC8qKlxyXG5cdCAqIE9wZW5zIHRoZSBtb2RhbCB3aW5kb3dcclxuXHQgKi9cclxuXHRBcHAucHJvdG90eXBlLm9wZW5Nb2RhbCA9IGZ1bmN0aW9uKCl7XHJcblx0XHQvLyBPbmx5IGFsbG93IGFuIGluc3RhbmNlIG9mIHRoZSBtb2RhbFZpZXdcclxuXHRcdGlmKCB0aGlzLm1vZGFsVmlldyA9PT0gdW5kZWZpbmVkICl7XHJcblx0XHRcdHRoaXMubW9kYWxWaWV3ID0gbmV3IE1vZGFsVmlldyh7Y29sbGVjdGlvbjogdGhpcy5zaG9ydGNvZGVzQ29sbGVjdGlvbiwgYXBwIDogdGhpc30pO1xyXG5cdFx0fVxyXG5cdH07XHJcblxyXG5cdC8qKlxyXG5cdCAqIE9wZW5zIHRoZSBtb2RhbCB3aW5kb3dcclxuXHQgKi9cclxuXHRBcHAucHJvdG90eXBlLmNsb3NlTW9kYWwgPSBmdW5jdGlvbigpe1xyXG5cdFx0dGhpcy5tb2RhbFZpZXcgPSB1bmRlZmluZWQ7XHJcblx0fTtcclxuXHJcblx0em5oZy5zaG9ydGNvZGVzTWFuYWdlciA9IG5ldyBBcHAoKS5zdGFydCgpO1xyXG5cclxufSkoalF1ZXJ5KTtcclxuIiwidmFyIG5hdlZpZXcgPSByZXF1aXJlKCcuL25hdlZpZXcnKTtcclxuXHJcbm1vZHVsZS5leHBvcnRzID0gQmFja2JvbmUuVmlldy5leHRlbmQoe1xyXG5cdGlkOiBcInpuaGd0Znctc2hvcnRjb2Rlcy1tb2RhbFwiLFxyXG5cdHRlbXBsYXRlIDogcmVxdWlyZSgnLi4vaHRtbC9tb2RhbC5odG1sJyksXHJcblx0ZXZlbnRzIDoge1xyXG5cdFx0J2NsaWNrIC56bmhndGZ3LW1vZGFsLWJhY2tkcm9wJzogJ21vZGFsQ2xvc2UnLFxyXG5cdFx0J2NsaWNrIC5tZWRpYS1tb2RhbC1jbG9zZSc6ICAgICAgJ21vZGFsQ2xvc2UnLFxyXG5cdFx0J2NsaWNrIC56bmhnLXNob3J0Y29kZS1pbnNlcnQnOiAgJ2luc2VydFNob3J0Y29kZSdcclxuXHR9LFxyXG5cdGluaXRpYWxpemUgOiBmdW5jdGlvbiggb3B0aW9ucyApe1xyXG5cdFx0dGhpcy5tYWluQXBwID0gb3B0aW9ucy5hcHA7XHJcblx0XHR0aGlzLmxpc3RlblRvKHRoaXMuY29sbGVjdGlvbiwgJ3Nob3J0Y29kZVNlbGVjdGVkJywgdGhpcy5yZW5kZXJQYXJhbXMpO1xyXG5cdFx0dGhpcy5yZW5kZXIoKTtcclxuXHR9LFxyXG5cdHJlbmRlciA6IGZ1bmN0aW9uKCl7XHJcblx0XHR0aGlzLiRlbC5odG1sKCB0aGlzLnRlbXBsYXRlKCkgKTtcclxuXHJcblx0XHQvLyBBZGQgdGhlIG5hdmlnYXRpb25cclxuXHRcdHRoaXMuJCgnLnpuaGd0ZnctbW9kYWwtc2lkZWJhcicpLmFwcGVuZCggbmV3IG5hdlZpZXcoKS5yZW5kZXIoKS4kZWwgKTtcclxuXHJcblx0XHQvLyBGaW5hbGx5Li4gYWRkIHRoZSBtb2RhbCB0byB0aGUgcGFnZVxyXG5cdFx0alF1ZXJ5KCAnYm9keScgKS5hcHBlbmQoIHRoaXMuJGVsICkuYWRkQ2xhc3MoJ3puaGd0ZnctbW9kYWwtb3BlbicpO1xyXG5cclxuXHRcdHJldHVybiB0aGlzO1xyXG5cdH0sXHJcblx0bW9kYWxDbG9zZSA6IGZ1bmN0aW9uKCl7XHJcblx0XHR0aGlzLiRlbC5yZW1vdmUoKTtcclxuXHRcdGpRdWVyeSgnYm9keScpLnJlbW92ZUNsYXNzKCd6bmhndGZ3LW1vZGFsLW9wZW4nKTtcclxuXHRcdHRoaXMubWFpbkFwcC5jbG9zZU1vZGFsKCk7XHJcblx0XHR0aGlzLnJlbW92ZSgpO1xyXG5cdH0sXHJcblx0cmVuZGVyUGFyYW1zOiBmdW5jdGlvbiggc2hvcnRjb2RlICl7XHJcblx0XHQvLyBXZSB3aWxsIG5lZWQgdG8gcmVuZGVyIHRoZSBmb3JtXHJcblx0XHR0aGlzLnBhcmFtc0NvbGxlY3Rpb24gPSB6bmhnLm9wdGlvbnNNYWNoaW5lLnNldHVwUGFyYW1zKCBzaG9ydGNvZGUuZ2V0KCdwYXJhbXMnKSApO1xyXG5cdFx0dmFyIGZvcm0gPSB6bmhnLm9wdGlvbnNNYWNoaW5lLnJlbmRlck9wdGlvbnNHcm91cCh0aGlzLnBhcmFtc0NvbGxlY3Rpb24pO1xyXG5cdFx0dGhpcy4kKCcuem5oZ3Rmdy1tb2RhbC1jb250ZW50JykuaHRtbChmb3JtKTtcclxuXHR9LFxyXG5cdGluc2VydFNob3J0Y29kZSA6IGZ1bmN0aW9uKHNob3J0Y29kZSl7XHJcblxyXG5cdFx0dmFyIHNob3J0Y29kZVRhZyAgICA9IHRoaXMuY29sbGVjdGlvbi5zZWxlY3RlZC5nZXQoICdpZCcgKSxcclxuXHRcdFx0Y2hhbmdlZFBhcmFtcyAgID0gdGhpcy5wYXJhbXNDb2xsZWN0aW9uLndoZXJlKHsgaXNDaGFuZ2VkOiB0cnVlIH0pLFxyXG5cdFx0XHRjbG9zZVNob3J0Y29kZSAgPSB0aGlzLmNvbGxlY3Rpb24uc2VsZWN0ZWQuZ2V0KCAnaGFzQ29udGVudCcgKSB8fCBmYWxzZSxcclxuXHRcdFx0c2hvcnRjb2RlQ29udGVudCA9IHRoaXMuY29sbGVjdGlvbi5zZWxlY3RlZC5nZXQoICdkZWZhdWx0Q29udGVudCcgKSB8fCBmYWxzZSxcclxuXHRcdFx0b3V0cHV0O1xyXG5cclxuXHRcdC8vIE9wZW4gdGhlIHNob3J0Y29kZSB0YWdcclxuXHRcdG91dHB1dCA9ICdbJysgc2hvcnRjb2RlVGFnO1xyXG5cdFx0XHQvLyBvdXRwdXQgYWxsIHRoZSBzaG9ydGNvZGUgcGFyYW1zL2F0dHJpYnV0ZXNcclxuXHRcdFx0Xy5lYWNoKGNoYW5nZWRQYXJhbXMsIGZ1bmN0aW9uKHBhcmFtKXtcclxuXHRcdFx0XHQvLyBEb24ndCBhZGQgdGhlIGNvbnRlbnQgYXR0cmlidXRlXHJcblx0XHRcdFx0aWYoIHBhcmFtLmdldCgnaWQnKSA9PT0gJ2NvbnRlbnQnICl7XHJcblx0XHRcdFx0XHQvLyBTZXQgdGhlIGNsb3NlU2hvcnRjb2RlIHRvIHRydWVcclxuXHRcdFx0XHRcdGNsb3NlU2hvcnRjb2RlID0gdHJ1ZTtcclxuXHRcdFx0XHRcdHNob3J0Y29kZUNvbnRlbnQgPSBwYXJhbS5nZXQoJ3ZhbHVlJyk7XHJcblx0XHRcdFx0XHRyZXR1cm4gdHJ1ZTtcclxuXHRcdFx0XHR9XHJcblx0XHRcdFx0Ly8gT3V0cHV0IHRoZSBwYXJhbV9uYW1lPXBhcmFtX3ZhbHVlXHJcblx0XHRcdFx0b3V0cHV0ICs9ICcgJysgcGFyYW0uZ2V0KCdpZCcpICsgJz1cIicgKyBwYXJhbS5nZXQoJ3ZhbHVlJykgKydcIic7XHJcblx0XHRcdH0pO1xyXG5cdFx0b3V0cHV0ICs9ICddJztcclxuXHJcblx0XHQvLyBJZiB3ZSBoYXZlIGNvbnRlbnQsIGFkZCB0aGUgY29udGVudCBhbmQgYWxzbyBhZGQgdGhlIGNsb3NpbmcgdGFnXHJcblx0XHRpZiAoIHNob3J0Y29kZUNvbnRlbnQgKSB7XHJcblx0XHRcdG91dHB1dCArPSBzaG9ydGNvZGVDb250ZW50O1xyXG5cdFx0fVxyXG5cclxuXHRcdC8vIENoZWNrIGlmIHdlIG5lZWQgdG8gY2xvc2UgdGhlIHNob3J0Y29kZVxyXG5cdFx0aWYoIGNsb3NlU2hvcnRjb2RlICl7XHJcblx0XHRcdG91dHB1dCArPSAnWy8nICsgc2hvcnRjb2RlVGFnICsgJ10nO1xyXG5cdFx0fVxyXG5cclxuXHRcdHdpbmRvdy53cC5tZWRpYS5lZGl0b3IuaW5zZXJ0KCBvdXRwdXQgKTtcclxuXHRcdHRoaXMubW9kYWxDbG9zZSgpO1xyXG5cdH1cclxufSk7IiwibW9kdWxlLmV4cG9ydHMgPSBCYWNrYm9uZS5WaWV3LmV4dGVuZCh7XHJcblx0dGFnTmFtZSA6ICdsaScsXHJcblx0ZXZlbnRzIDoge1xyXG5cdFx0J2NsaWNrJyA6ICdzZWxlY3RTaG9ydGNvZGUnXHJcblx0fSxcclxuXHRyZW5kZXIgOiBmdW5jdGlvbigpe1xyXG5cdFx0dGhpcy4kZWwuaHRtbCggalF1ZXJ5KCc8YSBocmVmPVwiI1wiPicgKyB0aGlzLm1vZGVsLmdldCgnbmFtZScpICsgJzwvYT4nKSApO1xyXG5cdFx0cmV0dXJuIHRoaXM7XHJcblx0fSxcclxuXHRzZWxlY3RTaG9ydGNvZGUgOiBmdW5jdGlvbigpe1xyXG5cdFx0dGhpcy5tb2RlbC5zZXRTZWxlY3RlZCgpO1xyXG5cdH1cclxufSk7IiwidmFyIG5hdkl0ZW0gPSByZXF1aXJlKCcuL25hdkl0ZW0nKTtcclxubW9kdWxlLmV4cG9ydHMgPSBCYWNrYm9uZS5WaWV3LmV4dGVuZCh7XHJcblx0dGFnTmFtZTogJ3VsJyxcclxuXHRjbGFzc05hbWUgOiAnem5oZ3Rmdy1tb2RhbC1tZW51LWRyb3Bkb3duJyxcclxuXHRyZW5kZXIgOiBmdW5jdGlvbigpe1xyXG5cdFx0dGhpcy5jb2xsZWN0aW9uLmVhY2goZnVuY3Rpb24oIHNob3J0Y29kZSApe1xyXG5cdFx0XHR0aGlzLiRlbC5hcHBlbmQobmV3IG5hdkl0ZW0oe21vZGVsOiBzaG9ydGNvZGV9KS5yZW5kZXIoKS4kZWwpO1xyXG5cdFx0fS5iaW5kKHRoaXMpKTtcclxuXHRcdHJldHVybiB0aGlzO1xyXG5cdH1cclxufSk7IiwidmFyIG5hdlNlY3Rpb24gPSByZXF1aXJlKCcuL25hdlNlY3Rpb24nKTtcclxubW9kdWxlLmV4cG9ydHMgPSBCYWNrYm9uZS5WaWV3LmV4dGVuZCh7XHJcblx0dGFnTmFtZTogJ3VsJyxcclxuXHRjbGFzc05hbWUgOiAnem5oZ3Rmdy1tb2RhbC1tZW51JyxcclxuXHRldmVudHMgOiB7XHJcblx0XHQnY2xpY2sgPiBsaSA+IGEnIDogJ3RvZ2dsZVNlY3Rpb24nXHJcblx0fSxcclxuXHRyZW5kZXIgOiBmdW5jdGlvbigpe1xyXG5cdFx0Xyh6bmhnU2hvcnRjb2Rlc01hbmFnZXJEYXRhLnNlY3Rpb25zKS5lYWNoKGZ1bmN0aW9uKHNlY3Rpb25OYW1lKXtcclxuXHRcdFx0dmFyICRsaSA9IGpRdWVyeSgnPGxpPjwvbGk+Jyk7XHJcblx0XHRcdCRsaS5hcHBlbmQoJzxhIGhyZWY9XCIjXCI+Jysgc2VjdGlvbk5hbWUgKyc8L2E+Jyk7XHJcblx0XHRcdCRsaS5hcHBlbmQoIG5ldyBuYXZTZWN0aW9uKCB7IGNvbGxlY3Rpb246IHpuaGcuc2hvcnRjb2Rlc01hbmFnZXIuc2hvcnRjb2Rlc0NvbGxlY3Rpb24uYnlTZWN0aW9uKCBzZWN0aW9uTmFtZSApIH0gKS5yZW5kZXIoKS4kZWwgKTtcclxuXHRcdFx0dGhpcy4kZWwuYXBwZW5kKCRsaSk7XHJcblx0XHR9LmJpbmQodGhpcykpO1xyXG5cdFx0cmV0dXJuIHRoaXM7XHJcblx0fSxcclxuXHR0b2dnbGVTZWN0aW9uIDogZnVuY3Rpb24oZSl7XHJcblx0XHR0aGlzLiRlbC5maW5kKCdsaScpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKTtcclxuXHRcdGpRdWVyeShlLnRhcmdldCkucGFyZW50KCkuYWRkQ2xhc3MoJ2FjdGl2ZScpO1xyXG5cdH1cclxufSk7Il19
