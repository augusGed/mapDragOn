'use strict';
/***************
coding model :
function initialize() {
        var fenway = {lat: 42.345573, lng: -71.098326};
        var map = new google.maps.Map(document.getElementById('map'), {
          center: fenway,
          zoom: 14
        });
        var panorama = new google.maps.StreetViewPanorama(
            document.getElementById('pano'), {
              position: fenway,
              pov: {
                heading: 34,
                pitch: 10
              }
            });
        map.setStreetView(panorama);
}

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initialize">
    </script>
***************/
var adMDra_globals ={
	state : {
		hassDblclickedForAPositionChange : false
	},
	dom : {
		map : undefined
	}
};
function initialize() {		
    
	var eiffelTower = {lat:48.8583882, lng:2.2945999};
	var map = new google.maps.Map(document.querySelector('.searching_space > .map:first-child'), {
      center: eiffelTower,
      zoom: 16
    });
    var panorama = new google.maps.StreetViewPanorama(
        //$('.searching_space > .pano:nth-child(2):nth-of-type(2)').get(0)
        document.querySelector('.searching_space > .pano:nth-child(2):nth-of-type(2)'), {
          position: eiffelTower,
          pov: {
            heading: 34,
            pitch: 10
          }
        });
    map.setStreetView(panorama);
    google.maps.event.addListenerOnce(map,'click', onFirstClickShowDataSpacePanel);
    google.maps.event.addListener(map,'dblclick', onDblclickSetNewMapPointLocation);
    adMDra_globals.dom.map=map;
    
}

function onFirstClickShowDataSpacePanel(e){
	document.querySelector('.section-editing-image_editing > .data_space').classList.remove('no-height');
}
function onDblclickSetNewMapPointLocation(e){
	adMDra_globals.state.hassDblclickedForAPositionChange = true;
	google.maps.event.addListenerOnce(this.streetView,'position_changed', onPositionChangedAfterDblclickReadData);
	this.setZoom(16);
	this.getStreetView().setPosition(e.latLng);
	//google.maps.event.trigger(this.getStreetView(), 'position_changed', [e.latLng, this] );
}

function onPositionChangedAfterDblclickReadData(e){
	console.log('after dblclick');
	if (!adMDra_globals.state.hassDblclickedForAPositionChange) return;
	var ds = $('section.section-editing-image_editing > .data_space');
	var thisRef = adMDra_globals.dom.map; //this;
	/* see : https://developers.google.com/maps/documentation/javascript/3.exp/reference */
	/* see : https://developers.google.com/maps/documentation/javascript/3.exp/reference#StreetViewLink */
	var uncapitalize = function(str){
		return str[0].toLowerCase() + str.slice(1);
	};
	var properties={
		map : {
			ref : (x=> thisRef),
			params :{
				Zoom : function(x){ return ["getZoom", x.getZoom];},
				Center : ( x=> ["getCenter", x.getCenter] ),
				Bounds : ( x=> ["getBounds", x.getBounds] ),
				Heading : (x => ["getHeading", x.getHeading] ),
				ClickableIcons : (x=> ["getClickableIcons", x.getClickableIcons]),
				Tilt : (x=> ["getTilt", x.getTilt])
			}
		},
		GetStreetView : {
			ref : (x=> thisRef.getStreetView()),
			params : ['getLinks','getLocation','getMotionTracking','getPano','getPhotographerPov','getPosition','getPov',
				'getStatus','getVisible','getZoom']
				.reduce( ((acc,x)=> (acc[(x.split(/^get/)[1])]=
					(y=> [x, y[x[0].toLowerCase()+x.slice(1) ] ] ) , acc) )
				 , {}
				)
		}

	};
	var g_property_dict = {
		getPov : {
			heading : "heading",
			pitch : "pitch"
		},
		getLinks : {
		 	description : "description",
		 	heading: "heading",
		 	pano : "pano"
		 }
		 ,
		 getLocation : {
		 	description : "description",
		 	latLng : "latLng",
		 	pano : "pano",
		 	shortDescription : "shortDescription"
		 }

	};
	g_property_dict['getPhotographerPov'] = g_property_dict.getPov;
	ds.html( Object.entries(properties).map(function(x)
		{ 
			var r=x[1].ref(); 
			return '\n<div>\n'.concat('<strong>',x[0],'</strong>', 
				'\t\n<ul class="data_stairs">\n',
				Object.entries(x[1].params).map(function(y,j){
					var a=(y[1](r));
					var b=(a[1].bind(r))();
					var c='get'+y[0];
					var res= ('\t\t<em>'+y[0]+'</em>').concat('\n'/*'\n\t<ul>\n'*/).concat( 
						 '\t\t<li>'.concat(a[0],' : ', b, '</li>\n' )  
					);
					if (y[0]==="Location" ) console.log("b Location : " + b["description"]);
					if ((c) in g_property_dict){ 
						var d=b ? (b.length>>1) : undefined;
						res += "\n\t"+'\t\t'+"<ul>\n".concat(
							Object.entries(g_property_dict[ c ]).map(
								z=> ( 
									b ? (console.log("b[1] data : " + (b[1] || "undefined") )  , ( "\t\t"+'\t\t'+"<li>".concat( 
									 ( y[0]==="Links" ? 
									  z[1]+" - "+b.map(
										(w,i)=>"\n".concat(
											'\t'.repeat(5),"<p>", '<mark class="mark-small">'.concat(
												"NearestStreetView other weblinks"+ i //(i > d ? 'StreetViewPanorama' : 'Map' )
												, '</mark>' 
											).concat(" : ", (w[z[1]]===undefined ? "undefined" : w[z[1]]))+"</p>\n"
										)
									  ).join("")
									  : z[1] + " : " + b[z[1]])
									  , "</li>\n"
									 ))
									)  : "" 
								) 								
							).join(''),
							"\t"+'\t\t'+"</ul>\n"
							) ;
					}
					//console.log("RES value ".concat(j, " A=",a," B=",b,":\n", res) );
					return res;
				}).join(''/*'\n\t</ul>\n<ul>\n'*/)
			 ,'\n\t</ul>\n</div>\n');
			
		}
	 ).join('' /*'\n</div>\n<div>\n'*/) );
	adMDra_globals.state.hassDblclickedForAPositionChange = false;
	
	//this.setPosition(e_args[0]);
}

(function() {$(document).ready(function(){
	$('section.section-editing-image_editing.tag-hidden').removeClass('tag-hidden');

})} )();