;jQuery.fn.extend({
	// Andrea Giammarchi - Mit Style Licence - V0.2
	If:function(fn){
		var __If__ = this.__If__ || this,
		$ = __If__.filter(fn);
		$.__If__ = __If__.filter(function(){return !~$.index(this)});
		return $;
	},
	Else:function(){
		return this.__If__;
	},
	Do:jQuery.fn.each
}); 
jQuery.fn.ElseIf = jQuery.fn.If;