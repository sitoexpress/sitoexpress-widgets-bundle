document.addEventListener("DOMContentLoaded", function() {

	let se_just_images = document.querySelectorAll('.se-grid-item');

	let elems = document.querySelectorAll('.se-masonry-grid');

		elems.forEach((elem, i) => {

		let msnry = new Masonry( elem, {
		  // options
		  itemSelector: '.se-grid-item',
		  columnWidth: '.se-grid-sizer',
			percentPosition: true,
			gutter: 0
		})

		imagesLoaded( grid ).on( 'progress', function() {
			// layout Masonry after each image loads
			msnry.layout();
		});

	})

})
