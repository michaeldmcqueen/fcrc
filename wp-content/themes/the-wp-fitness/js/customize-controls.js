( function( api ) {

	// Extends our custom "the-wp-fitness" section.
	api.sectionConstructor['the-wp-fitness'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );