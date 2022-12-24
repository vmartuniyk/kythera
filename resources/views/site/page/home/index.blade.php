@extends('site.layout.default-new')

@section('title')
    {{ $page->title }}
@stop


@section('content')
    <div class="container">
		
		<section class="first-screen">
			<div class="main-sreen-mobile">
				<picture><source srcset="../assets/img/main-screen-mob-bg.webp" type="image/webp"><img src="../assets/img/main-screen-mob-bg.jpg?_v=1655485994518" alt=""></picture>
			</div>
			<div class="first-screen__container">
				<div class="first-screen__content">
					<div class="first-screen__label section-label">DISCOVER YOUR HERITAGE</div>
					<h1 class="first-screen__title">Connecting Kytherians All Across The World</h1>
					<p class="first-screen__text">
						Our Kythera-Family.net is a cultural archive system which invites all people who love the Greek island of Kythera to enjoy the material which other members of the community have shared. It is a free service supported by the Kytherian Association of Australia to help promote the connection of Kythera with it's Diaspora all over the world. More than twenty thousand items - family trees, photographs, life stories etc. - have been submitted. It's free and easy to create an account to add your family history. And the site can of course be browsed by anyone without registering.
					</p>
				</div>
				<div class="first-screen__buttons">
					<a href="#" class="first-screen__family-btn btn btn-two-color">
						Search Family Records
						<svg class="icon-arrow-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
							<g id="Group_12" data-name="Group 12" transform="translate(-833.866 0.693)">
								<path id="Path_39" data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" />
								<line id="Line_2" data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" />
							</g>
						</svg>
					</a>
					<a href="#" class="first-screen__upload-btn btn btn-one-color">
						Upload Your Entry
						<svg class="icon-arrow-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
							<g id="Group_12" data-name="Group 12" transform="translate(-833.866 0.693)">
								<path id="Path_39" data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" />
								<line id="Line_2" data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" />
							</g>
						</svg>
					</a>
				</div>
			</div>
		</section>
		<section class="inner-section history">
			<div class="inner-section__container">
				<div class="inner-section__wrap">
					<div class="inner-section__image">
						<picture><source srcset="../assets/img/history.webp" type="image/webp"><img src="../assets/img/history.jpg?_v=1655485994518" alt=""></picture>
					</div>
					<div class="inner-section__info">
						<div class="inner-section__label section-label">APHRODITE’S ISLAND</div>
						<h2 class="inner-section__title">A Rich History</h2>
						<p class="inner-section__text">
							Discover the rich history of Kythera, an island steeped in Greek culture and mythology. Known as the birthplace of Aphrodite, Kythera was central to the Greek empire and carries those stories with it today. Learn more about this beautiful place.
						</p>
						<a href="#" class="inner-section__btn btn btn-accent-color">
							Discover More History
							<svg class="icon-arrow-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
								<g id="Group_12" data-name="Group 12" transform="translate(-833.866 0.693)">
									<path id="Path_39" data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke="#f9f7f2" stroke-miterlimit="10" stroke-width="2" />
									<line id="Line_2" data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke="#f9f7f2" stroke-miterlimit="10" stroke-width="2" />
								</g>
							</svg>
						</a>
					</div>
				</div>
			</div>
		</section>

		@include('site.page.home.blocks.posts')
	
		<section class="inner-section travel">
			<div class="inner-section__container">
				<div class="inner-section__wrap">
					<div class="inner-section__image">
						<picture><source srcset="../assets/img/history.webp" type="image/webp"><img src="../assets/img/history.jpg?_v=1655485994518" alt=""></picture>
					</div>
					<div class="inner-section__info">
						<div class="inner-section__label section-label">Travel Tips</div>
						<h2 class="inner-section__title">Plan A Trip</h2>
						<p class="inner-section__text">
							Insert overview text about visiting the island and what attractions are available. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim…
						</p>
						<a href="#" class="inner-section__btn btn btn-three-color">
							Start Planning
							<svg class="icon-arrow-btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.826 17.386">
								<g id="Group_12" data-name="Group 12" transform="translate(-833.866 0.693)">
									<path id="Path_39" data-name="Path 39" d="M848.614,724l7.69,8-7.69,8" transform="translate(0 -724)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" />
									<line id="Line_2" data-name="Line 2" x1="21.613" transform="translate(833.866 8)" fill="none" stroke="#fff" stroke-miterlimit="10" stroke-width="2" />
								</g>
							</svg>
						</a>
					</div>
				</div>
			</div>
		</section>
		
        
        
		<section class="accordion accordion-home">
			<div class="accordion__container">
				<div class="accordion-home__top">
					<h3 class="accordion__title section-label">Frequent Questions</h3>
					<a href="#" class="accordion__link-view view-link">
						View All Questions
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23.132 16.693">
							<g data-name="Group 23" transform="translate(-1292.865 -1328.645)">
								<g data-name="Group 22">
									<path data-name="Path 40" d="M1307.614,1328.991l7.691,8-7.691,8" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
								</g>
								<line data-name="Line 3" x1="21.613" transform="translate(1292.865 1336.991)" fill="none" stroke="#24646d" stroke-miterlimit="10" stroke-width="1" />
							</g>
						</svg>
					</a>
				</div>

				<div class="accordion__menu menu-accordion" data-spollers data-one-spoller>
					<div class="menu-accordion__item">
						<h3 class="menu-accordion__title _spoller-active" data-spoller>How do I find out if my ancestors were from Kythera?</h3>
						<p class="accordion-home__item-text">
							Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facili.
						</p>
					</div>
					<div class="menu-accordion__item">
						<h3 class="menu-accordion__title" data-spoller>What’s the best time of year to visit Kythera?</h3>
						<p class="accordion-home__item-text">
							Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facili.
						</p>
					</div>
					<div class="menu-accordion__item">
						<h3 class="menu-accordion__title" data-spoller>How do I contact my Kytherian relatives?</h3>
						<p class="accordion-home__item-text">
							Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facili.
						</p>
					</div>
					<div class="menu-accordion__item">
						<h3 class="menu-accordion__title" data-spoller>How can I donate to the Kytherian Association of Australia?</h3>
						<p class="accordion-home__item-text">
							Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facili.
						</p>
					</div>
				</div>
			</div>
		</section>
@stop
