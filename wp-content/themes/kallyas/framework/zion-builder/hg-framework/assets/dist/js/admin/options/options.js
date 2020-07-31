(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 if( name.length > 0 ) { 
__p+='\r\n\t<h4 class="znhg-option-title">\r\n\t\t'+
((__t=( name ))==null?'':__t)+
'\r\n\t</h4>\r\n';
 } 
__p+='\r\n';
 if( description.length > 0 ) { 
__p+='\r\n\t<div class="znhg-option-description">\r\n\t\t'+
((__t=( description ))==null?'':__t)+
'\r\n\t</div>\r\n';
 } 
__p+='\r\n<div class="znhg-option-content">\r\n\t<div class="input-append color">\r\n\t\t<input id="znhg-control-id-'+
((__t=( id ))==null?'':__t)+
'" type="text" class="znhg-color-picker" data-default-color="'+
((__t=( value ))==null?'':__t)+
'" name="'+
((__t=( id ))==null?'':__t)+
'" ';
 if( alpha ) { 
__p+=' data-alpha="true" ';
 } 
__p+=' value="'+
((__t=( value ))==null?'':__t)+
'" >\r\n\t</div>\r\n</div>';
}
return __p;
};

},{}],2:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='<div class="znhg-option-container">\r\n\t';
 if( name.length > 0 ) { 
__p+='\r\n\t\tthis is the name\r\n\t';
 } 
__p+='\r\n\t';
 if( description.length > 0 ) { 
__p+='\r\n\t\tthis is the description\r\n\t';
 } 
__p+='\r\n\t<div class="znhg-option-content"></div>\r\n</div>';
}
return __p;
};

},{}],3:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 if( name.length > 0 ) { 
__p+='\r\n\t<h4 class="znhg-option-title">\r\n\t\t'+
((__t=( name ))==null?'':__t)+
'\r\n\t</h4>\r\n';
 } 
__p+='\r\n';
 if( description.length > 0 ) { 
__p+='\r\n\t<div class="znhg-option-description">\r\n\t\t'+
((__t=( description ))==null?'':__t)+
'\r\n\t</div>\r\n';
 } 
__p+='\r\n<div class="znhg-option-content">\r\n\t<div class="znhg-group-option-container"></div>\r\n\t<div class="znhg-group-option-add">Add more</div>\r\n\t<!-- <input type="text" name="'+
((__t=( id ))==null?'':__t)+
'" id="znhg-control-id-'+
((__t=( id ))==null?'':__t)+
'" value="'+
((__t=( value ))==null?'':__t)+
'" ';
 if( placeholder.length > 0 ) { 
__p+=' placeholder="'+
((__t=( placeholder ))==null?'':__t)+
'" ';
 } 
__p+='> -->\r\n</div>';
}
return __p;
};

},{}],4:[function(require,module,exports){
arguments[4][3][0].apply(exports,arguments)
},{"dup":3}],5:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 if( name.length > 0 ) { 
__p+='\r\n\t<h4 class="znhg-option-title">\r\n\t\t'+
((__t=( name ))==null?'':__t)+
'\r\n\t</h4>\r\n';
 } 
__p+='\r\n';
 if( description.length > 0 ) { 
__p+='\r\n\t<div class="znhg-option-description">\r\n\t\t'+
((__t=( description ))==null?'':__t)+
'\r\n\t</div>\r\n';
 } 
__p+='\r\n<div class="znhg-option-content">\r\n\t<select name="'+
((__t=( id ))==null?'':__t)+
'" id="znhg-control-id-'+
((__t=( id ))==null?'':__t)+
'" ';
 if( multiple ) { 
__p+=' multiple ';
 } 
__p+='>\r\n\t\t<!-- <option value="volvo">Volvo</option> -->\r\n\t\t';
 _.each(options, function(name, id) {
			var selected = _.isArray(value) ? _.indexOf( value, id ) : value == id,
				selectedString = selected ? 'selected' : '';
		
__p+='\r\n\t\t\t<option value="'+
((__t=( id ))==null?'':__t)+
'" '+
((__t=( selectedString ))==null?'':__t)+
'>'+
((__t=( name ))==null?'':__t)+
'</option>\r\n\t\t';
 }); 
__p+='\r\n\t</select>\r\n</div>';
}
return __p;
};

},{}],6:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 if( name.length > 0 ) { 
__p+='\r\n\t<h4 class="znhg-option-title">\r\n\t\t'+
((__t=( name ))==null?'':__t)+
'\r\n\t</h4>\r\n';
 } 
__p+='\r\n';
 if( description.length > 0 ) { 
__p+='\r\n\t<div class="znhg-option-description">\r\n\t\t'+
((__t=( description ))==null?'':__t)+
'\r\n\t</div>\r\n';
 } 
__p+='\r\n<div class="znhg-option-content">\r\n\t<div class="zn_slider">\r\n\t\t<input id="znhg-control-id-'+
((__t=( id ))==null?'':__t)+
'" type="number" class="wp-slider-input" name="'+
((__t=( id ))==null?'':__t)+
'" value="'+
((__t=( parseInt(value) ))==null?'':__t)+
'">\r\n\t\t<div class="wp-slider znhg-slider-control"></div>\r\n\t</div>\r\n</div>';
}
return __p;
};

},{}],7:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 if( name.length > 0 ) { 
__p+='\r\n\t<h4 class="znhg-option-title">\r\n\t\t'+
((__t=( name ))==null?'':__t)+
'\r\n\t</h4>\r\n';
 } 
__p+='\r\n';
 if( description.length > 0 ) { 
__p+='\r\n\t<div class="znhg-option-description">\r\n\t\t'+
((__t=( description ))==null?'':__t)+
'\r\n\t</div>\r\n';
 } 
__p+='\r\n<div class="znhg-option-content">\r\n\t<input type="text" name="'+
((__t=( id ))==null?'':__t)+
'" id="znhg-control-id-'+
((__t=( id ))==null?'':__t)+
'" value="'+
((__t=( value ))==null?'':__t)+
'" ';
 if( placeholder.length > 0 ) { 
__p+=' placeholder="'+
((__t=( placeholder ))==null?'':__t)+
'" ';
 } 
__p+='>\r\n</div>';
}
return __p;
};

},{}],8:[function(require,module,exports){
module.exports = function(obj){
var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};
with(obj||{}){
__p+='';
 if( name.length > 0 ) { 
__p+='\r\n\t<h4 class="znhg-option-title">\r\n\t\t'+
((__t=( name ))==null?'':__t)+
'\r\n\t</h4>\r\n';
 } 
__p+='\r\n';
 if( description.length > 0 ) { 
__p+='\r\n\t<div class="znhg-option-description">\r\n\t\t'+
((__t=( description ))==null?'':__t)+
'\r\n\t</div>\r\n';
 } 
__p+='\r\n<div class="znhg-option-content">\r\n\t<textarea id="znhg-control-id-'+
((__t=( id ))==null?'':__t)+
'" name="'+
((__t=( id ))==null?'':__t)+
'" value="'+
((__t=( value ))==null?'':__t)+
'"></textarea>\r\n</div>';
}
return __p;
};

},{}],9:[function(require,module,exports){
module.exports = Backbone.Model.extend({
	defaults: {
		id: 'generic-param',
		title: 'Generic Param',
		description: '',
		placeholder: '',
		type: 'text',
		default_value: "",
		value: '',
		dependency: null,
		live: null,
		isChanged : false, // if the option value was changed
		options: false, // for select option ?
		multiple : false, // only for select option ?
		alpha : false, // only for colorpicker option ?
		min: 0,
		max: 100,
		disabled: false,
		step: 1,
		subelements: []
	}
});
},{}],10:[function(require,module,exports){
module.exports = Backbone.Collection.extend( { model: require('./param-model') } );
},{"./param-model":9}],11:[function(require,module,exports){
window.znhg = window.znhg || {};

(function ($){
	var App = {};

	// Will hold a refference to all options types registered
	App.optionsType = {
		'text' : require('./views/options/text'),
		'textarea' : require('./views/options/textarea'),
		'select' : require('./views/options/select'),
		'colorpicker' : require('./views/options/colorpicker'),
		'slider' : require('./views/options/slider'),
		'group' : require('./views/options/group')
	};

	// Will hold a refference to all options types registered
	App.optionsDisplayType = {
		'default' : require('./views/options_display_type/default')
	};

	App.start = function(){
		return this;
	};


	/**
	 * Will register an option type
	 * @param  {string} optionId   The option type unique id
	 * @param  {object} optionView The option view.
	 */
	App.registerOption = function( optionId, optionView ){
		this.optionsType[optionId] = optionView;
	};


	/**
	 * Creates a backbone collection containing all the params. Can be used to easily access the params
	 * @param  {object} params The params object
	 * @return {object}        An instance of the controls collection
	 */
	App.setupParams = function( params ){
		var paramsCollection = require('./models/params-collection');
		return new paramsCollection(params);
	};


	/**
	 * Will unregister an option type
	 * @param  {string} optionId   The option type unique id
	 */
	App.unregisterOption = function(optionId){
		delete this.optionsType[optionId];
	};


	App.renderForm = function(){
		// Will rener a form that has saving capabilities
	};


	/**
	 * Will render an option group
	 * Unlike options forms, options group doesn't have saving capabilities
	 * @param  {object} params The params that needs to be rendered
	 * @return {string}        The HTML markup for the form
	 */
	App.renderOptionsGroup = function( controlsCollection ){
		var optionsGroupView = require('./views/forms/group');
		return new optionsGroupView({ collection : controlsCollection, controller : this }).render().$el;
	};

	znhg.optionsMachine = App.start();
}(jQuery));
},{"./models/params-collection":10,"./views/forms/group":12,"./views/options/colorpicker":14,"./views/options/group":15,"./views/options/select":17,"./views/options/slider":18,"./views/options/text":19,"./views/options/textarea":20,"./views/options_display_type/default":21}],12:[function(require,module,exports){
module.exports = Backbone.View.extend({
	className : 'znhg-options-group',
	initialize : function( options ){
		this.controller = options.controller;
	},
	render : function(){
		this.collection.each(function( param ){
			var optionType = param.get('type');
			if( typeof this.controller.optionsType[optionType] !== 'undefined' ){
				this.$el.append( new this.controller.optionsType[optionType]({model : param}).render().$el );
			}
			else{
				console.info('It seems that the "'+optionType+'" option type doesn\'t exists or it wasn\'t registered');
			}
		}.bind(this));

		return this;
	}
});
},{}],13:[function(require,module,exports){
module.exports = Backbone.View.extend({
	className: 'znhg-option-container',
	render : function(){
		this.controlRender();
		this.afterRender();
		this.activateControl();
		return this;
	},
	controlRender : function(){
		this.$el.addClass( 'znhg-option-type-'+ this.model.get('type') );
		this.$el.html( this.template( this.model.toJSON() ) );
		return this;
	},
	afterRender: function(){
		// This should be override by the child class
		return this;
	},
	activateControl : function(){
		var that = this;
		// Here we will activate extra functionality for this param
		this.$('#znhg-control-id-'+ this.model.get('id') ).on('change', function(e){
			that.setValue( jQuery(this).val() );
		});
	},
	setValue : function( newValue ){

		var oldValue = this.model.get('value');
		newValue = this.validateValue( newValue );

		// We will set the value if it validate
		if( null !== newValue && newValue !== oldValue ){
			this.model.set('value', newValue);
			if( this.model.get('type') == 'select' ){
				console.log(newValue);
			}

			this.model.set( 'isChanged', true );
		}
	},
	validateValue : function( value ){
		return value;
	}
});
},{}],14:[function(require,module,exports){
var baseParam = require( './base' );
module.exports = baseParam.extend({
	template: require('../../html/colorpicker.html'),
	render : function(){
		this.controlRender();
		this.$('.znhg-color-picker').wpColorPicker({
			change: this.colorChange.bind(this),
			defaultWidth: '65'
		});
		return this;
	},
	colorChange: function(event, ui){
		this.setValue( ui.color.toString() );
	}
});
},{"../../html/colorpicker.html":1,"./base":13}],15:[function(require,module,exports){
var baseParamView = require( './base' );
var groupItemView = require( './group_item' );
module.exports = baseParamView.extend({
	template: require('../../html/group.html'),
	afterRender: function(){

		this.itemsContainer = this.$('.znhg-group-option-container');

		// Check if we have saved values
		var values = this.model.get('value');
		if (values.length) {
			_.each(values, function(itemValue) {
				this.addItem(itemValue);
			}.bind(this));
		}

		return this;
	},
	addItem: function( groupItem ){
		var paramsCollection = znhg.optionsMachine.setupParams( this.model.get('subelements') );

		var item = new groupItemView({
			values : groupItem,
			collection: paramsCollection
		}).render();

		this.itemsContainer.append(item.$el);

		return this;
	}
});
},{"../../html/group.html":3,"./base":13,"./group_item":16}],16:[function(require,module,exports){
module.exports = Backbone.View.extend({
	template: require('../../html/group_item.html'),
	initialize: function(options){
		this.values = options.values;

	},
	render: function(){
		this.setValues();
		var form = znhg.optionsMachine.renderOptionsGroup(this.collection);
		this.$el.html( form );
		return this;
	},
	// If we have saved values, we should add them to the option
	setValues: function(){
		this.collection.each(function(model){
			console.log(model);
			if( this.values[model.get('id')].length > 0 ){
				model.set('value', this.values[model.get('id')] );
			}
		}.bind(this));
	}
});
},{"../../html/group_item.html":4}],17:[function(require,module,exports){
var baseParam = require( './base' );
module.exports = baseParam.extend({
	template: require('../../html/select.html'),
});
},{"../../html/select.html":5,"./base":13}],18:[function(require,module,exports){
var baseParam = require( './base' );
module.exports = baseParam.extend({
	template: require('../../html/slider.html'),
	events : {
		'change .wp-slider-input' : 'inputChange'
	},
	afterRender : function(){

		this.slider = this.$('.znhg-slider-control');
		var input = this.$('.wp-slider-input');

		this.slider.slider({
			range: "max",
			disabled: this.model.get('disabled'),
			min: this.model.get('minimumValue'),
			max: this.model.get('maximumValue'),
			value: this.model.get('value'),
			step: this.model.get('step'),
			slide: function( event, ui ) {
				input.val( ui.value );
			}
		});

		return this;
	},
	inputChange: function(e){

		var minimumVal = parseInt( this.model.get('minimumValue') ),
			maximumVal = parseInt( this.model.get('maximumValue') ),
			newValue   = parseInt( jQuery(e.currentTarget).val() );

		if( newValue < minimumVal ) { jQuery(e.currentTarget).val( minimumVal ); }
		if( newValue > maximumVal ) { jQuery(e.currentTarget).val( maximumVal ); }

		// CHECK IF THE INPUT IS NOT A NUMBER
		if( isNaN(newValue) ) { jQuery(this).val( minimumVal ); }

		this.slider.slider("value" ,  newValue );
	}
});
},{"../../html/slider.html":6,"./base":13}],19:[function(require,module,exports){
var baseParam = require( './base' );
module.exports = baseParam.extend({
	template: require('../../html/text.html'),
});
},{"../../html/text.html":7,"./base":13}],20:[function(require,module,exports){
var baseParam = require( './base' );
module.exports = baseParam.extend({
	template: require('../../html/textarea.html'),
});
},{"../../html/textarea.html":8,"./base":13}],21:[function(require,module,exports){
module.exports = Backbone.View.extend({

	template : require( '../../html/default_option_type_display.html' ),
	// className: 'znhg-option-container',
	initialize : function( options ){
		this.controller = options.controller;
	},
	render : function(){
		this.$el.html( this.template( this.model.toJSON() ) );
		this.$('.znhg-option-content').html( new this.controller.optionsType[this.model.get('type')]({model : this.model}).render().$el );
		return this;
	},
});
},{"../../html/default_option_type_display.html":2}]},{},[11])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIi4uL25vZGVfbW9kdWxlcy9icm93c2VyLXBhY2svX3ByZWx1ZGUuanMiLCJhc3NldHMvc3JjL2pzL2FkbWluL29wdGlvbnMvaHRtbC9jb2xvcnBpY2tlci5odG1sIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL2h0bWwvZGVmYXVsdF9vcHRpb25fdHlwZV9kaXNwbGF5Lmh0bWwiLCJhc3NldHMvc3JjL2pzL2FkbWluL29wdGlvbnMvaHRtbC9ncm91cC5odG1sIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL2h0bWwvc2VsZWN0Lmh0bWwiLCJhc3NldHMvc3JjL2pzL2FkbWluL29wdGlvbnMvaHRtbC9zbGlkZXIuaHRtbCIsImFzc2V0cy9zcmMvanMvYWRtaW4vb3B0aW9ucy9odG1sL3RleHQuaHRtbCIsImFzc2V0cy9zcmMvanMvYWRtaW4vb3B0aW9ucy9odG1sL3RleHRhcmVhLmh0bWwiLCJhc3NldHMvc3JjL2pzL2FkbWluL29wdGlvbnMvbW9kZWxzL3BhcmFtLW1vZGVsLmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL21vZGVscy9wYXJhbXMtY29sbGVjdGlvbi5qcyIsImFzc2V0cy9zcmMvanMvYWRtaW4vb3B0aW9ucy9vcHRpb25zLmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL3ZpZXdzL2Zvcm1zL2dyb3VwLmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL3ZpZXdzL29wdGlvbnMvYmFzZS5qcyIsImFzc2V0cy9zcmMvanMvYWRtaW4vb3B0aW9ucy92aWV3cy9vcHRpb25zL2NvbG9ycGlja2VyLmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL3ZpZXdzL29wdGlvbnMvZ3JvdXAuanMiLCJhc3NldHMvc3JjL2pzL2FkbWluL29wdGlvbnMvdmlld3Mvb3B0aW9ucy9ncm91cF9pdGVtLmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL3ZpZXdzL29wdGlvbnMvc2VsZWN0LmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL3ZpZXdzL29wdGlvbnMvc2xpZGVyLmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL3ZpZXdzL29wdGlvbnMvdGV4dC5qcyIsImFzc2V0cy9zcmMvanMvYWRtaW4vb3B0aW9ucy92aWV3cy9vcHRpb25zL3RleHRhcmVhLmpzIiwiYXNzZXRzL3NyYy9qcy9hZG1pbi9vcHRpb25zL3ZpZXdzL29wdGlvbnNfZGlzcGxheV90eXBlL2RlZmF1bHQuanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUNBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQy9CQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUNmQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7O0FDL0JBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDeENBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDekJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDL0JBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDekJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ3JCQTs7QUNBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUN4RUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDbEJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQzFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDZEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FDOUJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQ3JCQTtBQUNBO0FBQ0E7QUFDQTs7QUNIQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUN2Q0E7QUFDQTtBQUNBO0FBQ0E7O0FDSEE7QUFDQTtBQUNBO0FBQ0E7O0FDSEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiBlKHQsbixyKXtmdW5jdGlvbiBzKG8sdSl7aWYoIW5bb10pe2lmKCF0W29dKXt2YXIgYT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2lmKCF1JiZhKXJldHVybiBhKG8sITApO2lmKGkpcmV0dXJuIGkobywhMCk7dmFyIGY9bmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIitvK1wiJ1wiKTt0aHJvdyBmLmNvZGU9XCJNT0RVTEVfTk9UX0ZPVU5EXCIsZn12YXIgbD1uW29dPXtleHBvcnRzOnt9fTt0W29dWzBdLmNhbGwobC5leHBvcnRzLGZ1bmN0aW9uKGUpe3ZhciBuPXRbb11bMV1bZV07cmV0dXJuIHMobj9uOmUpfSxsLGwuZXhwb3J0cyxlLHQsbixyKX1yZXR1cm4gbltvXS5leHBvcnRzfXZhciBpPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7Zm9yKHZhciBvPTA7bzxyLmxlbmd0aDtvKyspcyhyW29dKTtyZXR1cm4gc30pIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbihvYmope1xudmFyIF9fdCxfX3A9JycsX19qPUFycmF5LnByb3RvdHlwZS5qb2luLHByaW50PWZ1bmN0aW9uKCl7X19wKz1fX2ouY2FsbChhcmd1bWVudHMsJycpO307XG53aXRoKG9ianx8e30pe1xuX19wKz0nJztcbiBpZiggbmFtZS5sZW5ndGggPiAwICkgeyBcbl9fcCs9J1xcclxcblxcdDxoNCBjbGFzcz1cInpuaGctb3B0aW9uLXRpdGxlXCI+XFxyXFxuXFx0XFx0JytcbigoX190PSggbmFtZSApKT09bnVsbD8nJzpfX3QpK1xuJ1xcclxcblxcdDwvaDQ+XFxyXFxuJztcbiB9IFxuX19wKz0nXFxyXFxuJztcbiBpZiggZGVzY3JpcHRpb24ubGVuZ3RoID4gMCApIHsgXG5fX3ArPSdcXHJcXG5cXHQ8ZGl2IGNsYXNzPVwiem5oZy1vcHRpb24tZGVzY3JpcHRpb25cIj5cXHJcXG5cXHRcXHQnK1xuKChfX3Q9KCBkZXNjcmlwdGlvbiApKT09bnVsbD8nJzpfX3QpK1xuJ1xcclxcblxcdDwvZGl2Plxcclxcbic7XG4gfSBcbl9fcCs9J1xcclxcbjxkaXYgY2xhc3M9XCJ6bmhnLW9wdGlvbi1jb250ZW50XCI+XFxyXFxuXFx0PGRpdiBjbGFzcz1cImlucHV0LWFwcGVuZCBjb2xvclwiPlxcclxcblxcdFxcdDxpbnB1dCBpZD1cInpuaGctY29udHJvbC1pZC0nK1xuKChfX3Q9KCBpZCApKT09bnVsbD8nJzpfX3QpK1xuJ1wiIHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJ6bmhnLWNvbG9yLXBpY2tlclwiIGRhdGEtZGVmYXVsdC1jb2xvcj1cIicrXG4oKF9fdD0oIHZhbHVlICkpPT1udWxsPycnOl9fdCkrXG4nXCIgbmFtZT1cIicrXG4oKF9fdD0oIGlkICkpPT1udWxsPycnOl9fdCkrXG4nXCIgJztcbiBpZiggYWxwaGEgKSB7IFxuX19wKz0nIGRhdGEtYWxwaGE9XCJ0cnVlXCIgJztcbiB9IFxuX19wKz0nIHZhbHVlPVwiJytcbigoX190PSggdmFsdWUgKSk9PW51bGw/Jyc6X190KStcbidcIiA+XFxyXFxuXFx0PC9kaXY+XFxyXFxuPC9kaXY+Jztcbn1cbnJldHVybiBfX3A7XG59O1xuIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbihvYmope1xudmFyIF9fdCxfX3A9JycsX19qPUFycmF5LnByb3RvdHlwZS5qb2luLHByaW50PWZ1bmN0aW9uKCl7X19wKz1fX2ouY2FsbChhcmd1bWVudHMsJycpO307XG53aXRoKG9ianx8e30pe1xuX19wKz0nPGRpdiBjbGFzcz1cInpuaGctb3B0aW9uLWNvbnRhaW5lclwiPlxcclxcblxcdCc7XG4gaWYoIG5hbWUubGVuZ3RoID4gMCApIHsgXG5fX3ArPSdcXHJcXG5cXHRcXHR0aGlzIGlzIHRoZSBuYW1lXFxyXFxuXFx0JztcbiB9IFxuX19wKz0nXFxyXFxuXFx0JztcbiBpZiggZGVzY3JpcHRpb24ubGVuZ3RoID4gMCApIHsgXG5fX3ArPSdcXHJcXG5cXHRcXHR0aGlzIGlzIHRoZSBkZXNjcmlwdGlvblxcclxcblxcdCc7XG4gfSBcbl9fcCs9J1xcclxcblxcdDxkaXYgY2xhc3M9XCJ6bmhnLW9wdGlvbi1jb250ZW50XCI+PC9kaXY+XFxyXFxuPC9kaXY+Jztcbn1cbnJldHVybiBfX3A7XG59O1xuIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbihvYmope1xudmFyIF9fdCxfX3A9JycsX19qPUFycmF5LnByb3RvdHlwZS5qb2luLHByaW50PWZ1bmN0aW9uKCl7X19wKz1fX2ouY2FsbChhcmd1bWVudHMsJycpO307XG53aXRoKG9ianx8e30pe1xuX19wKz0nJztcbiBpZiggbmFtZS5sZW5ndGggPiAwICkgeyBcbl9fcCs9J1xcclxcblxcdDxoNCBjbGFzcz1cInpuaGctb3B0aW9uLXRpdGxlXCI+XFxyXFxuXFx0XFx0JytcbigoX190PSggbmFtZSApKT09bnVsbD8nJzpfX3QpK1xuJ1xcclxcblxcdDwvaDQ+XFxyXFxuJztcbiB9IFxuX19wKz0nXFxyXFxuJztcbiBpZiggZGVzY3JpcHRpb24ubGVuZ3RoID4gMCApIHsgXG5fX3ArPSdcXHJcXG5cXHQ8ZGl2IGNsYXNzPVwiem5oZy1vcHRpb24tZGVzY3JpcHRpb25cIj5cXHJcXG5cXHRcXHQnK1xuKChfX3Q9KCBkZXNjcmlwdGlvbiApKT09bnVsbD8nJzpfX3QpK1xuJ1xcclxcblxcdDwvZGl2Plxcclxcbic7XG4gfSBcbl9fcCs9J1xcclxcbjxkaXYgY2xhc3M9XCJ6bmhnLW9wdGlvbi1jb250ZW50XCI+XFxyXFxuXFx0PGRpdiBjbGFzcz1cInpuaGctZ3JvdXAtb3B0aW9uLWNvbnRhaW5lclwiPjwvZGl2PlxcclxcblxcdDxkaXYgY2xhc3M9XCJ6bmhnLWdyb3VwLW9wdGlvbi1hZGRcIj5BZGQgbW9yZTwvZGl2PlxcclxcblxcdDwhLS0gPGlucHV0IHR5cGU9XCJ0ZXh0XCIgbmFtZT1cIicrXG4oKF9fdD0oIGlkICkpPT1udWxsPycnOl9fdCkrXG4nXCIgaWQ9XCJ6bmhnLWNvbnRyb2wtaWQtJytcbigoX190PSggaWQgKSk9PW51bGw/Jyc6X190KStcbidcIiB2YWx1ZT1cIicrXG4oKF9fdD0oIHZhbHVlICkpPT1udWxsPycnOl9fdCkrXG4nXCIgJztcbiBpZiggcGxhY2Vob2xkZXIubGVuZ3RoID4gMCApIHsgXG5fX3ArPScgcGxhY2Vob2xkZXI9XCInK1xuKChfX3Q9KCBwbGFjZWhvbGRlciApKT09bnVsbD8nJzpfX3QpK1xuJ1wiICc7XG4gfSBcbl9fcCs9Jz4gLS0+XFxyXFxuPC9kaXY+Jztcbn1cbnJldHVybiBfX3A7XG59O1xuIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbihvYmope1xudmFyIF9fdCxfX3A9JycsX19qPUFycmF5LnByb3RvdHlwZS5qb2luLHByaW50PWZ1bmN0aW9uKCl7X19wKz1fX2ouY2FsbChhcmd1bWVudHMsJycpO307XG53aXRoKG9ianx8e30pe1xuX19wKz0nJztcbiBpZiggbmFtZS5sZW5ndGggPiAwICkgeyBcbl9fcCs9J1xcclxcblxcdDxoNCBjbGFzcz1cInpuaGctb3B0aW9uLXRpdGxlXCI+XFxyXFxuXFx0XFx0JytcbigoX190PSggbmFtZSApKT09bnVsbD8nJzpfX3QpK1xuJ1xcclxcblxcdDwvaDQ+XFxyXFxuJztcbiB9IFxuX19wKz0nXFxyXFxuJztcbiBpZiggZGVzY3JpcHRpb24ubGVuZ3RoID4gMCApIHsgXG5fX3ArPSdcXHJcXG5cXHQ8ZGl2IGNsYXNzPVwiem5oZy1vcHRpb24tZGVzY3JpcHRpb25cIj5cXHJcXG5cXHRcXHQnK1xuKChfX3Q9KCBkZXNjcmlwdGlvbiApKT09bnVsbD8nJzpfX3QpK1xuJ1xcclxcblxcdDwvZGl2Plxcclxcbic7XG4gfSBcbl9fcCs9J1xcclxcbjxkaXYgY2xhc3M9XCJ6bmhnLW9wdGlvbi1jb250ZW50XCI+XFxyXFxuXFx0PHNlbGVjdCBuYW1lPVwiJytcbigoX190PSggaWQgKSk9PW51bGw/Jyc6X190KStcbidcIiBpZD1cInpuaGctY29udHJvbC1pZC0nK1xuKChfX3Q9KCBpZCApKT09bnVsbD8nJzpfX3QpK1xuJ1wiICc7XG4gaWYoIG11bHRpcGxlICkgeyBcbl9fcCs9JyBtdWx0aXBsZSAnO1xuIH0gXG5fX3ArPSc+XFxyXFxuXFx0XFx0PCEtLSA8b3B0aW9uIHZhbHVlPVwidm9sdm9cIj5Wb2x2bzwvb3B0aW9uPiAtLT5cXHJcXG5cXHRcXHQnO1xuIF8uZWFjaChvcHRpb25zLCBmdW5jdGlvbihuYW1lLCBpZCkge1xyXG5cdFx0XHR2YXIgc2VsZWN0ZWQgPSBfLmlzQXJyYXkodmFsdWUpID8gXy5pbmRleE9mKCB2YWx1ZSwgaWQgKSA6IHZhbHVlID09IGlkLFxyXG5cdFx0XHRcdHNlbGVjdGVkU3RyaW5nID0gc2VsZWN0ZWQgPyAnc2VsZWN0ZWQnIDogJyc7XHJcblx0XHRcbl9fcCs9J1xcclxcblxcdFxcdFxcdDxvcHRpb24gdmFsdWU9XCInK1xuKChfX3Q9KCBpZCApKT09bnVsbD8nJzpfX3QpK1xuJ1wiICcrXG4oKF9fdD0oIHNlbGVjdGVkU3RyaW5nICkpPT1udWxsPycnOl9fdCkrXG4nPicrXG4oKF9fdD0oIG5hbWUgKSk9PW51bGw/Jyc6X190KStcbic8L29wdGlvbj5cXHJcXG5cXHRcXHQnO1xuIH0pOyBcbl9fcCs9J1xcclxcblxcdDwvc2VsZWN0PlxcclxcbjwvZGl2Pic7XG59XG5yZXR1cm4gX19wO1xufTtcbiIsIm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24ob2JqKXtcbnZhciBfX3QsX19wPScnLF9faj1BcnJheS5wcm90b3R5cGUuam9pbixwcmludD1mdW5jdGlvbigpe19fcCs9X19qLmNhbGwoYXJndW1lbnRzLCcnKTt9O1xud2l0aChvYmp8fHt9KXtcbl9fcCs9Jyc7XG4gaWYoIG5hbWUubGVuZ3RoID4gMCApIHsgXG5fX3ArPSdcXHJcXG5cXHQ8aDQgY2xhc3M9XCJ6bmhnLW9wdGlvbi10aXRsZVwiPlxcclxcblxcdFxcdCcrXG4oKF9fdD0oIG5hbWUgKSk9PW51bGw/Jyc6X190KStcbidcXHJcXG5cXHQ8L2g0Plxcclxcbic7XG4gfSBcbl9fcCs9J1xcclxcbic7XG4gaWYoIGRlc2NyaXB0aW9uLmxlbmd0aCA+IDAgKSB7IFxuX19wKz0nXFxyXFxuXFx0PGRpdiBjbGFzcz1cInpuaGctb3B0aW9uLWRlc2NyaXB0aW9uXCI+XFxyXFxuXFx0XFx0JytcbigoX190PSggZGVzY3JpcHRpb24gKSk9PW51bGw/Jyc6X190KStcbidcXHJcXG5cXHQ8L2Rpdj5cXHJcXG4nO1xuIH0gXG5fX3ArPSdcXHJcXG48ZGl2IGNsYXNzPVwiem5oZy1vcHRpb24tY29udGVudFwiPlxcclxcblxcdDxkaXYgY2xhc3M9XCJ6bl9zbGlkZXJcIj5cXHJcXG5cXHRcXHQ8aW5wdXQgaWQ9XCJ6bmhnLWNvbnRyb2wtaWQtJytcbigoX190PSggaWQgKSk9PW51bGw/Jyc6X190KStcbidcIiB0eXBlPVwibnVtYmVyXCIgY2xhc3M9XCJ3cC1zbGlkZXItaW5wdXRcIiBuYW1lPVwiJytcbigoX190PSggaWQgKSk9PW51bGw/Jyc6X190KStcbidcIiB2YWx1ZT1cIicrXG4oKF9fdD0oIHBhcnNlSW50KHZhbHVlKSApKT09bnVsbD8nJzpfX3QpK1xuJ1wiPlxcclxcblxcdFxcdDxkaXYgY2xhc3M9XCJ3cC1zbGlkZXIgem5oZy1zbGlkZXItY29udHJvbFwiPjwvZGl2PlxcclxcblxcdDwvZGl2PlxcclxcbjwvZGl2Pic7XG59XG5yZXR1cm4gX19wO1xufTtcbiIsIm1vZHVsZS5leHBvcnRzID0gZnVuY3Rpb24ob2JqKXtcbnZhciBfX3QsX19wPScnLF9faj1BcnJheS5wcm90b3R5cGUuam9pbixwcmludD1mdW5jdGlvbigpe19fcCs9X19qLmNhbGwoYXJndW1lbnRzLCcnKTt9O1xud2l0aChvYmp8fHt9KXtcbl9fcCs9Jyc7XG4gaWYoIG5hbWUubGVuZ3RoID4gMCApIHsgXG5fX3ArPSdcXHJcXG5cXHQ8aDQgY2xhc3M9XCJ6bmhnLW9wdGlvbi10aXRsZVwiPlxcclxcblxcdFxcdCcrXG4oKF9fdD0oIG5hbWUgKSk9PW51bGw/Jyc6X190KStcbidcXHJcXG5cXHQ8L2g0Plxcclxcbic7XG4gfSBcbl9fcCs9J1xcclxcbic7XG4gaWYoIGRlc2NyaXB0aW9uLmxlbmd0aCA+IDAgKSB7IFxuX19wKz0nXFxyXFxuXFx0PGRpdiBjbGFzcz1cInpuaGctb3B0aW9uLWRlc2NyaXB0aW9uXCI+XFxyXFxuXFx0XFx0JytcbigoX190PSggZGVzY3JpcHRpb24gKSk9PW51bGw/Jyc6X190KStcbidcXHJcXG5cXHQ8L2Rpdj5cXHJcXG4nO1xuIH0gXG5fX3ArPSdcXHJcXG48ZGl2IGNsYXNzPVwiem5oZy1vcHRpb24tY29udGVudFwiPlxcclxcblxcdDxpbnB1dCB0eXBlPVwidGV4dFwiIG5hbWU9XCInK1xuKChfX3Q9KCBpZCApKT09bnVsbD8nJzpfX3QpK1xuJ1wiIGlkPVwiem5oZy1jb250cm9sLWlkLScrXG4oKF9fdD0oIGlkICkpPT1udWxsPycnOl9fdCkrXG4nXCIgdmFsdWU9XCInK1xuKChfX3Q9KCB2YWx1ZSApKT09bnVsbD8nJzpfX3QpK1xuJ1wiICc7XG4gaWYoIHBsYWNlaG9sZGVyLmxlbmd0aCA+IDAgKSB7IFxuX19wKz0nIHBsYWNlaG9sZGVyPVwiJytcbigoX190PSggcGxhY2Vob2xkZXIgKSk9PW51bGw/Jyc6X190KStcbidcIiAnO1xuIH0gXG5fX3ArPSc+XFxyXFxuPC9kaXY+Jztcbn1cbnJldHVybiBfX3A7XG59O1xuIiwibW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbihvYmope1xudmFyIF9fdCxfX3A9JycsX19qPUFycmF5LnByb3RvdHlwZS5qb2luLHByaW50PWZ1bmN0aW9uKCl7X19wKz1fX2ouY2FsbChhcmd1bWVudHMsJycpO307XG53aXRoKG9ianx8e30pe1xuX19wKz0nJztcbiBpZiggbmFtZS5sZW5ndGggPiAwICkgeyBcbl9fcCs9J1xcclxcblxcdDxoNCBjbGFzcz1cInpuaGctb3B0aW9uLXRpdGxlXCI+XFxyXFxuXFx0XFx0JytcbigoX190PSggbmFtZSApKT09bnVsbD8nJzpfX3QpK1xuJ1xcclxcblxcdDwvaDQ+XFxyXFxuJztcbiB9IFxuX19wKz0nXFxyXFxuJztcbiBpZiggZGVzY3JpcHRpb24ubGVuZ3RoID4gMCApIHsgXG5fX3ArPSdcXHJcXG5cXHQ8ZGl2IGNsYXNzPVwiem5oZy1vcHRpb24tZGVzY3JpcHRpb25cIj5cXHJcXG5cXHRcXHQnK1xuKChfX3Q9KCBkZXNjcmlwdGlvbiApKT09bnVsbD8nJzpfX3QpK1xuJ1xcclxcblxcdDwvZGl2Plxcclxcbic7XG4gfSBcbl9fcCs9J1xcclxcbjxkaXYgY2xhc3M9XCJ6bmhnLW9wdGlvbi1jb250ZW50XCI+XFxyXFxuXFx0PHRleHRhcmVhIGlkPVwiem5oZy1jb250cm9sLWlkLScrXG4oKF9fdD0oIGlkICkpPT1udWxsPycnOl9fdCkrXG4nXCIgbmFtZT1cIicrXG4oKF9fdD0oIGlkICkpPT1udWxsPycnOl9fdCkrXG4nXCIgdmFsdWU9XCInK1xuKChfX3Q9KCB2YWx1ZSApKT09bnVsbD8nJzpfX3QpK1xuJ1wiPjwvdGV4dGFyZWE+XFxyXFxuPC9kaXY+Jztcbn1cbnJldHVybiBfX3A7XG59O1xuIiwibW9kdWxlLmV4cG9ydHMgPSBCYWNrYm9uZS5Nb2RlbC5leHRlbmQoe1xyXG5cdGRlZmF1bHRzOiB7XHJcblx0XHRpZDogJ2dlbmVyaWMtcGFyYW0nLFxyXG5cdFx0dGl0bGU6ICdHZW5lcmljIFBhcmFtJyxcclxuXHRcdGRlc2NyaXB0aW9uOiAnJyxcclxuXHRcdHBsYWNlaG9sZGVyOiAnJyxcclxuXHRcdHR5cGU6ICd0ZXh0JyxcclxuXHRcdGRlZmF1bHRfdmFsdWU6IFwiXCIsXHJcblx0XHR2YWx1ZTogJycsXHJcblx0XHRkZXBlbmRlbmN5OiBudWxsLFxyXG5cdFx0bGl2ZTogbnVsbCxcclxuXHRcdGlzQ2hhbmdlZCA6IGZhbHNlLCAvLyBpZiB0aGUgb3B0aW9uIHZhbHVlIHdhcyBjaGFuZ2VkXHJcblx0XHRvcHRpb25zOiBmYWxzZSwgLy8gZm9yIHNlbGVjdCBvcHRpb24gP1xyXG5cdFx0bXVsdGlwbGUgOiBmYWxzZSwgLy8gb25seSBmb3Igc2VsZWN0IG9wdGlvbiA/XHJcblx0XHRhbHBoYSA6IGZhbHNlLCAvLyBvbmx5IGZvciBjb2xvcnBpY2tlciBvcHRpb24gP1xyXG5cdFx0bWluOiAwLFxyXG5cdFx0bWF4OiAxMDAsXHJcblx0XHRkaXNhYmxlZDogZmFsc2UsXHJcblx0XHRzdGVwOiAxLFxyXG5cdFx0c3ViZWxlbWVudHM6IFtdXHJcblx0fVxyXG59KTsiLCJtb2R1bGUuZXhwb3J0cyA9IEJhY2tib25lLkNvbGxlY3Rpb24uZXh0ZW5kKCB7IG1vZGVsOiByZXF1aXJlKCcuL3BhcmFtLW1vZGVsJykgfSApOyIsIndpbmRvdy56bmhnID0gd2luZG93LnpuaGcgfHwge307XHJcblxyXG4oZnVuY3Rpb24gKCQpe1xyXG5cdHZhciBBcHAgPSB7fTtcclxuXHJcblx0Ly8gV2lsbCBob2xkIGEgcmVmZmVyZW5jZSB0byBhbGwgb3B0aW9ucyB0eXBlcyByZWdpc3RlcmVkXHJcblx0QXBwLm9wdGlvbnNUeXBlID0ge1xyXG5cdFx0J3RleHQnIDogcmVxdWlyZSgnLi92aWV3cy9vcHRpb25zL3RleHQnKSxcclxuXHRcdCd0ZXh0YXJlYScgOiByZXF1aXJlKCcuL3ZpZXdzL29wdGlvbnMvdGV4dGFyZWEnKSxcclxuXHRcdCdzZWxlY3QnIDogcmVxdWlyZSgnLi92aWV3cy9vcHRpb25zL3NlbGVjdCcpLFxyXG5cdFx0J2NvbG9ycGlja2VyJyA6IHJlcXVpcmUoJy4vdmlld3Mvb3B0aW9ucy9jb2xvcnBpY2tlcicpLFxyXG5cdFx0J3NsaWRlcicgOiByZXF1aXJlKCcuL3ZpZXdzL29wdGlvbnMvc2xpZGVyJyksXHJcblx0XHQnZ3JvdXAnIDogcmVxdWlyZSgnLi92aWV3cy9vcHRpb25zL2dyb3VwJylcclxuXHR9O1xyXG5cclxuXHQvLyBXaWxsIGhvbGQgYSByZWZmZXJlbmNlIHRvIGFsbCBvcHRpb25zIHR5cGVzIHJlZ2lzdGVyZWRcclxuXHRBcHAub3B0aW9uc0Rpc3BsYXlUeXBlID0ge1xyXG5cdFx0J2RlZmF1bHQnIDogcmVxdWlyZSgnLi92aWV3cy9vcHRpb25zX2Rpc3BsYXlfdHlwZS9kZWZhdWx0JylcclxuXHR9O1xyXG5cclxuXHRBcHAuc3RhcnQgPSBmdW5jdGlvbigpe1xyXG5cdFx0cmV0dXJuIHRoaXM7XHJcblx0fTtcclxuXHJcblxyXG5cdC8qKlxyXG5cdCAqIFdpbGwgcmVnaXN0ZXIgYW4gb3B0aW9uIHR5cGVcclxuXHQgKiBAcGFyYW0gIHtzdHJpbmd9IG9wdGlvbklkICAgVGhlIG9wdGlvbiB0eXBlIHVuaXF1ZSBpZFxyXG5cdCAqIEBwYXJhbSAge29iamVjdH0gb3B0aW9uVmlldyBUaGUgb3B0aW9uIHZpZXcuXHJcblx0ICovXHJcblx0QXBwLnJlZ2lzdGVyT3B0aW9uID0gZnVuY3Rpb24oIG9wdGlvbklkLCBvcHRpb25WaWV3ICl7XHJcblx0XHR0aGlzLm9wdGlvbnNUeXBlW29wdGlvbklkXSA9IG9wdGlvblZpZXc7XHJcblx0fTtcclxuXHJcblxyXG5cdC8qKlxyXG5cdCAqIENyZWF0ZXMgYSBiYWNrYm9uZSBjb2xsZWN0aW9uIGNvbnRhaW5pbmcgYWxsIHRoZSBwYXJhbXMuIENhbiBiZSB1c2VkIHRvIGVhc2lseSBhY2Nlc3MgdGhlIHBhcmFtc1xyXG5cdCAqIEBwYXJhbSAge29iamVjdH0gcGFyYW1zIFRoZSBwYXJhbXMgb2JqZWN0XHJcblx0ICogQHJldHVybiB7b2JqZWN0fSAgICAgICAgQW4gaW5zdGFuY2Ugb2YgdGhlIGNvbnRyb2xzIGNvbGxlY3Rpb25cclxuXHQgKi9cclxuXHRBcHAuc2V0dXBQYXJhbXMgPSBmdW5jdGlvbiggcGFyYW1zICl7XHJcblx0XHR2YXIgcGFyYW1zQ29sbGVjdGlvbiA9IHJlcXVpcmUoJy4vbW9kZWxzL3BhcmFtcy1jb2xsZWN0aW9uJyk7XHJcblx0XHRyZXR1cm4gbmV3IHBhcmFtc0NvbGxlY3Rpb24ocGFyYW1zKTtcclxuXHR9O1xyXG5cclxuXHJcblx0LyoqXHJcblx0ICogV2lsbCB1bnJlZ2lzdGVyIGFuIG9wdGlvbiB0eXBlXHJcblx0ICogQHBhcmFtICB7c3RyaW5nfSBvcHRpb25JZCAgIFRoZSBvcHRpb24gdHlwZSB1bmlxdWUgaWRcclxuXHQgKi9cclxuXHRBcHAudW5yZWdpc3Rlck9wdGlvbiA9IGZ1bmN0aW9uKG9wdGlvbklkKXtcclxuXHRcdGRlbGV0ZSB0aGlzLm9wdGlvbnNUeXBlW29wdGlvbklkXTtcclxuXHR9O1xyXG5cclxuXHJcblx0QXBwLnJlbmRlckZvcm0gPSBmdW5jdGlvbigpe1xyXG5cdFx0Ly8gV2lsbCByZW5lciBhIGZvcm0gdGhhdCBoYXMgc2F2aW5nIGNhcGFiaWxpdGllc1xyXG5cdH07XHJcblxyXG5cclxuXHQvKipcclxuXHQgKiBXaWxsIHJlbmRlciBhbiBvcHRpb24gZ3JvdXBcclxuXHQgKiBVbmxpa2Ugb3B0aW9ucyBmb3Jtcywgb3B0aW9ucyBncm91cCBkb2Vzbid0IGhhdmUgc2F2aW5nIGNhcGFiaWxpdGllc1xyXG5cdCAqIEBwYXJhbSAge29iamVjdH0gcGFyYW1zIFRoZSBwYXJhbXMgdGhhdCBuZWVkcyB0byBiZSByZW5kZXJlZFxyXG5cdCAqIEByZXR1cm4ge3N0cmluZ30gICAgICAgIFRoZSBIVE1MIG1hcmt1cCBmb3IgdGhlIGZvcm1cclxuXHQgKi9cclxuXHRBcHAucmVuZGVyT3B0aW9uc0dyb3VwID0gZnVuY3Rpb24oIGNvbnRyb2xzQ29sbGVjdGlvbiApe1xyXG5cdFx0dmFyIG9wdGlvbnNHcm91cFZpZXcgPSByZXF1aXJlKCcuL3ZpZXdzL2Zvcm1zL2dyb3VwJyk7XHJcblx0XHRyZXR1cm4gbmV3IG9wdGlvbnNHcm91cFZpZXcoeyBjb2xsZWN0aW9uIDogY29udHJvbHNDb2xsZWN0aW9uLCBjb250cm9sbGVyIDogdGhpcyB9KS5yZW5kZXIoKS4kZWw7XHJcblx0fTtcclxuXHJcblx0em5oZy5vcHRpb25zTWFjaGluZSA9IEFwcC5zdGFydCgpO1xyXG59KGpRdWVyeSkpOyIsIm1vZHVsZS5leHBvcnRzID0gQmFja2JvbmUuVmlldy5leHRlbmQoe1xyXG5cdGNsYXNzTmFtZSA6ICd6bmhnLW9wdGlvbnMtZ3JvdXAnLFxyXG5cdGluaXRpYWxpemUgOiBmdW5jdGlvbiggb3B0aW9ucyApe1xyXG5cdFx0dGhpcy5jb250cm9sbGVyID0gb3B0aW9ucy5jb250cm9sbGVyO1xyXG5cdH0sXHJcblx0cmVuZGVyIDogZnVuY3Rpb24oKXtcclxuXHRcdHRoaXMuY29sbGVjdGlvbi5lYWNoKGZ1bmN0aW9uKCBwYXJhbSApe1xyXG5cdFx0XHR2YXIgb3B0aW9uVHlwZSA9IHBhcmFtLmdldCgndHlwZScpO1xyXG5cdFx0XHRpZiggdHlwZW9mIHRoaXMuY29udHJvbGxlci5vcHRpb25zVHlwZVtvcHRpb25UeXBlXSAhPT0gJ3VuZGVmaW5lZCcgKXtcclxuXHRcdFx0XHR0aGlzLiRlbC5hcHBlbmQoIG5ldyB0aGlzLmNvbnRyb2xsZXIub3B0aW9uc1R5cGVbb3B0aW9uVHlwZV0oe21vZGVsIDogcGFyYW19KS5yZW5kZXIoKS4kZWwgKTtcclxuXHRcdFx0fVxyXG5cdFx0XHRlbHNle1xyXG5cdFx0XHRcdGNvbnNvbGUuaW5mbygnSXQgc2VlbXMgdGhhdCB0aGUgXCInK29wdGlvblR5cGUrJ1wiIG9wdGlvbiB0eXBlIGRvZXNuXFwndCBleGlzdHMgb3IgaXQgd2FzblxcJ3QgcmVnaXN0ZXJlZCcpO1xyXG5cdFx0XHR9XHJcblx0XHR9LmJpbmQodGhpcykpO1xyXG5cclxuXHRcdHJldHVybiB0aGlzO1xyXG5cdH1cclxufSk7IiwibW9kdWxlLmV4cG9ydHMgPSBCYWNrYm9uZS5WaWV3LmV4dGVuZCh7XHJcblx0Y2xhc3NOYW1lOiAnem5oZy1vcHRpb24tY29udGFpbmVyJyxcclxuXHRyZW5kZXIgOiBmdW5jdGlvbigpe1xyXG5cdFx0dGhpcy5jb250cm9sUmVuZGVyKCk7XHJcblx0XHR0aGlzLmFmdGVyUmVuZGVyKCk7XHJcblx0XHR0aGlzLmFjdGl2YXRlQ29udHJvbCgpO1xyXG5cdFx0cmV0dXJuIHRoaXM7XHJcblx0fSxcclxuXHRjb250cm9sUmVuZGVyIDogZnVuY3Rpb24oKXtcclxuXHRcdHRoaXMuJGVsLmFkZENsYXNzKCAnem5oZy1vcHRpb24tdHlwZS0nKyB0aGlzLm1vZGVsLmdldCgndHlwZScpICk7XHJcblx0XHR0aGlzLiRlbC5odG1sKCB0aGlzLnRlbXBsYXRlKCB0aGlzLm1vZGVsLnRvSlNPTigpICkgKTtcclxuXHRcdHJldHVybiB0aGlzO1xyXG5cdH0sXHJcblx0YWZ0ZXJSZW5kZXI6IGZ1bmN0aW9uKCl7XHJcblx0XHQvLyBUaGlzIHNob3VsZCBiZSBvdmVycmlkZSBieSB0aGUgY2hpbGQgY2xhc3NcclxuXHRcdHJldHVybiB0aGlzO1xyXG5cdH0sXHJcblx0YWN0aXZhdGVDb250cm9sIDogZnVuY3Rpb24oKXtcclxuXHRcdHZhciB0aGF0ID0gdGhpcztcclxuXHRcdC8vIEhlcmUgd2Ugd2lsbCBhY3RpdmF0ZSBleHRyYSBmdW5jdGlvbmFsaXR5IGZvciB0aGlzIHBhcmFtXHJcblx0XHR0aGlzLiQoJyN6bmhnLWNvbnRyb2wtaWQtJysgdGhpcy5tb2RlbC5nZXQoJ2lkJykgKS5vbignY2hhbmdlJywgZnVuY3Rpb24oZSl7XHJcblx0XHRcdHRoYXQuc2V0VmFsdWUoIGpRdWVyeSh0aGlzKS52YWwoKSApO1xyXG5cdFx0fSk7XHJcblx0fSxcclxuXHRzZXRWYWx1ZSA6IGZ1bmN0aW9uKCBuZXdWYWx1ZSApe1xyXG5cclxuXHRcdHZhciBvbGRWYWx1ZSA9IHRoaXMubW9kZWwuZ2V0KCd2YWx1ZScpO1xyXG5cdFx0bmV3VmFsdWUgPSB0aGlzLnZhbGlkYXRlVmFsdWUoIG5ld1ZhbHVlICk7XHJcblxyXG5cdFx0Ly8gV2Ugd2lsbCBzZXQgdGhlIHZhbHVlIGlmIGl0IHZhbGlkYXRlXHJcblx0XHRpZiggbnVsbCAhPT0gbmV3VmFsdWUgJiYgbmV3VmFsdWUgIT09IG9sZFZhbHVlICl7XHJcblx0XHRcdHRoaXMubW9kZWwuc2V0KCd2YWx1ZScsIG5ld1ZhbHVlKTtcclxuXHRcdFx0aWYoIHRoaXMubW9kZWwuZ2V0KCd0eXBlJykgPT0gJ3NlbGVjdCcgKXtcclxuXHRcdFx0XHRjb25zb2xlLmxvZyhuZXdWYWx1ZSk7XHJcblx0XHRcdH1cclxuXHJcblx0XHRcdHRoaXMubW9kZWwuc2V0KCAnaXNDaGFuZ2VkJywgdHJ1ZSApO1xyXG5cdFx0fVxyXG5cdH0sXHJcblx0dmFsaWRhdGVWYWx1ZSA6IGZ1bmN0aW9uKCB2YWx1ZSApe1xyXG5cdFx0cmV0dXJuIHZhbHVlO1xyXG5cdH1cclxufSk7IiwidmFyIGJhc2VQYXJhbSA9IHJlcXVpcmUoICcuL2Jhc2UnICk7XHJcbm1vZHVsZS5leHBvcnRzID0gYmFzZVBhcmFtLmV4dGVuZCh7XHJcblx0dGVtcGxhdGU6IHJlcXVpcmUoJy4uLy4uL2h0bWwvY29sb3JwaWNrZXIuaHRtbCcpLFxyXG5cdHJlbmRlciA6IGZ1bmN0aW9uKCl7XHJcblx0XHR0aGlzLmNvbnRyb2xSZW5kZXIoKTtcclxuXHRcdHRoaXMuJCgnLnpuaGctY29sb3ItcGlja2VyJykud3BDb2xvclBpY2tlcih7XHJcblx0XHRcdGNoYW5nZTogdGhpcy5jb2xvckNoYW5nZS5iaW5kKHRoaXMpLFxyXG5cdFx0XHRkZWZhdWx0V2lkdGg6ICc2NSdcclxuXHRcdH0pO1xyXG5cdFx0cmV0dXJuIHRoaXM7XHJcblx0fSxcclxuXHRjb2xvckNoYW5nZTogZnVuY3Rpb24oZXZlbnQsIHVpKXtcclxuXHRcdHRoaXMuc2V0VmFsdWUoIHVpLmNvbG9yLnRvU3RyaW5nKCkgKTtcclxuXHR9XHJcbn0pOyIsInZhciBiYXNlUGFyYW1WaWV3ID0gcmVxdWlyZSggJy4vYmFzZScgKTtcclxudmFyIGdyb3VwSXRlbVZpZXcgPSByZXF1aXJlKCAnLi9ncm91cF9pdGVtJyApO1xyXG5tb2R1bGUuZXhwb3J0cyA9IGJhc2VQYXJhbVZpZXcuZXh0ZW5kKHtcclxuXHR0ZW1wbGF0ZTogcmVxdWlyZSgnLi4vLi4vaHRtbC9ncm91cC5odG1sJyksXHJcblx0YWZ0ZXJSZW5kZXI6IGZ1bmN0aW9uKCl7XHJcblxyXG5cdFx0dGhpcy5pdGVtc0NvbnRhaW5lciA9IHRoaXMuJCgnLnpuaGctZ3JvdXAtb3B0aW9uLWNvbnRhaW5lcicpO1xyXG5cclxuXHRcdC8vIENoZWNrIGlmIHdlIGhhdmUgc2F2ZWQgdmFsdWVzXHJcblx0XHR2YXIgdmFsdWVzID0gdGhpcy5tb2RlbC5nZXQoJ3ZhbHVlJyk7XHJcblx0XHRpZiAodmFsdWVzLmxlbmd0aCkge1xyXG5cdFx0XHRfLmVhY2godmFsdWVzLCBmdW5jdGlvbihpdGVtVmFsdWUpIHtcclxuXHRcdFx0XHR0aGlzLmFkZEl0ZW0oaXRlbVZhbHVlKTtcclxuXHRcdFx0fS5iaW5kKHRoaXMpKTtcclxuXHRcdH1cclxuXHJcblx0XHRyZXR1cm4gdGhpcztcclxuXHR9LFxyXG5cdGFkZEl0ZW06IGZ1bmN0aW9uKCBncm91cEl0ZW0gKXtcclxuXHRcdHZhciBwYXJhbXNDb2xsZWN0aW9uID0gem5oZy5vcHRpb25zTWFjaGluZS5zZXR1cFBhcmFtcyggdGhpcy5tb2RlbC5nZXQoJ3N1YmVsZW1lbnRzJykgKTtcclxuXHJcblx0XHR2YXIgaXRlbSA9IG5ldyBncm91cEl0ZW1WaWV3KHtcclxuXHRcdFx0dmFsdWVzIDogZ3JvdXBJdGVtLFxyXG5cdFx0XHRjb2xsZWN0aW9uOiBwYXJhbXNDb2xsZWN0aW9uXHJcblx0XHR9KS5yZW5kZXIoKTtcclxuXHJcblx0XHR0aGlzLml0ZW1zQ29udGFpbmVyLmFwcGVuZChpdGVtLiRlbCk7XHJcblxyXG5cdFx0cmV0dXJuIHRoaXM7XHJcblx0fVxyXG59KTsiLCJtb2R1bGUuZXhwb3J0cyA9IEJhY2tib25lLlZpZXcuZXh0ZW5kKHtcclxuXHR0ZW1wbGF0ZTogcmVxdWlyZSgnLi4vLi4vaHRtbC9ncm91cF9pdGVtLmh0bWwnKSxcclxuXHRpbml0aWFsaXplOiBmdW5jdGlvbihvcHRpb25zKXtcclxuXHRcdHRoaXMudmFsdWVzID0gb3B0aW9ucy52YWx1ZXM7XHJcblxyXG5cdH0sXHJcblx0cmVuZGVyOiBmdW5jdGlvbigpe1xyXG5cdFx0dGhpcy5zZXRWYWx1ZXMoKTtcclxuXHRcdHZhciBmb3JtID0gem5oZy5vcHRpb25zTWFjaGluZS5yZW5kZXJPcHRpb25zR3JvdXAodGhpcy5jb2xsZWN0aW9uKTtcclxuXHRcdHRoaXMuJGVsLmh0bWwoIGZvcm0gKTtcclxuXHRcdHJldHVybiB0aGlzO1xyXG5cdH0sXHJcblx0Ly8gSWYgd2UgaGF2ZSBzYXZlZCB2YWx1ZXMsIHdlIHNob3VsZCBhZGQgdGhlbSB0byB0aGUgb3B0aW9uXHJcblx0c2V0VmFsdWVzOiBmdW5jdGlvbigpe1xyXG5cdFx0dGhpcy5jb2xsZWN0aW9uLmVhY2goZnVuY3Rpb24obW9kZWwpe1xyXG5cdFx0XHRjb25zb2xlLmxvZyhtb2RlbCk7XHJcblx0XHRcdGlmKCB0aGlzLnZhbHVlc1ttb2RlbC5nZXQoJ2lkJyldLmxlbmd0aCA+IDAgKXtcclxuXHRcdFx0XHRtb2RlbC5zZXQoJ3ZhbHVlJywgdGhpcy52YWx1ZXNbbW9kZWwuZ2V0KCdpZCcpXSApO1xyXG5cdFx0XHR9XHJcblx0XHR9LmJpbmQodGhpcykpO1xyXG5cdH1cclxufSk7IiwidmFyIGJhc2VQYXJhbSA9IHJlcXVpcmUoICcuL2Jhc2UnICk7XHJcbm1vZHVsZS5leHBvcnRzID0gYmFzZVBhcmFtLmV4dGVuZCh7XHJcblx0dGVtcGxhdGU6IHJlcXVpcmUoJy4uLy4uL2h0bWwvc2VsZWN0Lmh0bWwnKSxcclxufSk7IiwidmFyIGJhc2VQYXJhbSA9IHJlcXVpcmUoICcuL2Jhc2UnICk7XHJcbm1vZHVsZS5leHBvcnRzID0gYmFzZVBhcmFtLmV4dGVuZCh7XHJcblx0dGVtcGxhdGU6IHJlcXVpcmUoJy4uLy4uL2h0bWwvc2xpZGVyLmh0bWwnKSxcclxuXHRldmVudHMgOiB7XHJcblx0XHQnY2hhbmdlIC53cC1zbGlkZXItaW5wdXQnIDogJ2lucHV0Q2hhbmdlJ1xyXG5cdH0sXHJcblx0YWZ0ZXJSZW5kZXIgOiBmdW5jdGlvbigpe1xyXG5cclxuXHRcdHRoaXMuc2xpZGVyID0gdGhpcy4kKCcuem5oZy1zbGlkZXItY29udHJvbCcpO1xyXG5cdFx0dmFyIGlucHV0ID0gdGhpcy4kKCcud3Atc2xpZGVyLWlucHV0Jyk7XHJcblxyXG5cdFx0dGhpcy5zbGlkZXIuc2xpZGVyKHtcclxuXHRcdFx0cmFuZ2U6IFwibWF4XCIsXHJcblx0XHRcdGRpc2FibGVkOiB0aGlzLm1vZGVsLmdldCgnZGlzYWJsZWQnKSxcclxuXHRcdFx0bWluOiB0aGlzLm1vZGVsLmdldCgnbWluaW11bVZhbHVlJyksXHJcblx0XHRcdG1heDogdGhpcy5tb2RlbC5nZXQoJ21heGltdW1WYWx1ZScpLFxyXG5cdFx0XHR2YWx1ZTogdGhpcy5tb2RlbC5nZXQoJ3ZhbHVlJyksXHJcblx0XHRcdHN0ZXA6IHRoaXMubW9kZWwuZ2V0KCdzdGVwJyksXHJcblx0XHRcdHNsaWRlOiBmdW5jdGlvbiggZXZlbnQsIHVpICkge1xyXG5cdFx0XHRcdGlucHV0LnZhbCggdWkudmFsdWUgKTtcclxuXHRcdFx0fVxyXG5cdFx0fSk7XHJcblxyXG5cdFx0cmV0dXJuIHRoaXM7XHJcblx0fSxcclxuXHRpbnB1dENoYW5nZTogZnVuY3Rpb24oZSl7XHJcblxyXG5cdFx0dmFyIG1pbmltdW1WYWwgPSBwYXJzZUludCggdGhpcy5tb2RlbC5nZXQoJ21pbmltdW1WYWx1ZScpICksXHJcblx0XHRcdG1heGltdW1WYWwgPSBwYXJzZUludCggdGhpcy5tb2RlbC5nZXQoJ21heGltdW1WYWx1ZScpICksXHJcblx0XHRcdG5ld1ZhbHVlICAgPSBwYXJzZUludCggalF1ZXJ5KGUuY3VycmVudFRhcmdldCkudmFsKCkgKTtcclxuXHJcblx0XHRpZiggbmV3VmFsdWUgPCBtaW5pbXVtVmFsICkgeyBqUXVlcnkoZS5jdXJyZW50VGFyZ2V0KS52YWwoIG1pbmltdW1WYWwgKTsgfVxyXG5cdFx0aWYoIG5ld1ZhbHVlID4gbWF4aW11bVZhbCApIHsgalF1ZXJ5KGUuY3VycmVudFRhcmdldCkudmFsKCBtYXhpbXVtVmFsICk7IH1cclxuXHJcblx0XHQvLyBDSEVDSyBJRiBUSEUgSU5QVVQgSVMgTk9UIEEgTlVNQkVSXHJcblx0XHRpZiggaXNOYU4obmV3VmFsdWUpICkgeyBqUXVlcnkodGhpcykudmFsKCBtaW5pbXVtVmFsICk7IH1cclxuXHJcblx0XHR0aGlzLnNsaWRlci5zbGlkZXIoXCJ2YWx1ZVwiICwgIG5ld1ZhbHVlICk7XHJcblx0fVxyXG59KTsiLCJ2YXIgYmFzZVBhcmFtID0gcmVxdWlyZSggJy4vYmFzZScgKTtcclxubW9kdWxlLmV4cG9ydHMgPSBiYXNlUGFyYW0uZXh0ZW5kKHtcclxuXHR0ZW1wbGF0ZTogcmVxdWlyZSgnLi4vLi4vaHRtbC90ZXh0Lmh0bWwnKSxcclxufSk7IiwidmFyIGJhc2VQYXJhbSA9IHJlcXVpcmUoICcuL2Jhc2UnICk7XHJcbm1vZHVsZS5leHBvcnRzID0gYmFzZVBhcmFtLmV4dGVuZCh7XHJcblx0dGVtcGxhdGU6IHJlcXVpcmUoJy4uLy4uL2h0bWwvdGV4dGFyZWEuaHRtbCcpLFxyXG59KTsiLCJtb2R1bGUuZXhwb3J0cyA9IEJhY2tib25lLlZpZXcuZXh0ZW5kKHtcclxuXHJcblx0dGVtcGxhdGUgOiByZXF1aXJlKCAnLi4vLi4vaHRtbC9kZWZhdWx0X29wdGlvbl90eXBlX2Rpc3BsYXkuaHRtbCcgKSxcclxuXHQvLyBjbGFzc05hbWU6ICd6bmhnLW9wdGlvbi1jb250YWluZXInLFxyXG5cdGluaXRpYWxpemUgOiBmdW5jdGlvbiggb3B0aW9ucyApe1xyXG5cdFx0dGhpcy5jb250cm9sbGVyID0gb3B0aW9ucy5jb250cm9sbGVyO1xyXG5cdH0sXHJcblx0cmVuZGVyIDogZnVuY3Rpb24oKXtcclxuXHRcdHRoaXMuJGVsLmh0bWwoIHRoaXMudGVtcGxhdGUoIHRoaXMubW9kZWwudG9KU09OKCkgKSApO1xyXG5cdFx0dGhpcy4kKCcuem5oZy1vcHRpb24tY29udGVudCcpLmh0bWwoIG5ldyB0aGlzLmNvbnRyb2xsZXIub3B0aW9uc1R5cGVbdGhpcy5tb2RlbC5nZXQoJ3R5cGUnKV0oe21vZGVsIDogdGhpcy5tb2RlbH0pLnJlbmRlcigpLiRlbCApO1xyXG5cdFx0cmV0dXJuIHRoaXM7XHJcblx0fSxcclxufSk7Il19
