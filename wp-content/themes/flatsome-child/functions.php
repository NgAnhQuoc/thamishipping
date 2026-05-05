<?php
// Add custom Theme Functions here

// Override flatsome_posted_on to show date with icon
function flatsome_posted_on()
{
	$date = get_the_date('d-m-Y');
	echo '<span class="posted-on"><i class="fa fa-calendar"></i> ' . $date . '</span>';
}

// Custom [wpseo_breadcrumb] shortcode - thay thế Yoast SEO breadcrumb
// Cấu trúc HTML giống y hệt Yoast SEO
if (!shortcode_exists('wpseo_breadcrumb')) {
	add_shortcode('wpseo_breadcrumb', function () {
		$separator = ' &raquo; ';
		$crumbs = [];

		// Trang chủ
		$crumbs[] = [
			'text' => 'Home',
			'url' => home_url('/'),
		];

		if (is_category()) {
			$category = get_queried_object();
			// Thêm category cha nếu có
			$ancestors = array_reverse(get_ancestors($category->term_id, 'category'));
			foreach ($ancestors as $ancestor_id) {
				$ancestor = get_category($ancestor_id);
				$crumbs[] = [
					'text' => $ancestor->name,
					'url' => get_category_link($ancestor_id),
				];
			}
			// Category hiện tại (cuối cùng, không có link)
			$crumbs[] = [
				'text' => $category->name,
				'url' => '',
			];
		} elseif (is_single()) {
			$categories = get_the_category();
			if (!empty($categories)) {
				$cat = $categories[0];
				// Thêm category cha nếu có
				$ancestors = array_reverse(get_ancestors($cat->term_id, 'category'));
				foreach ($ancestors as $ancestor_id) {
					$ancestor = get_category($ancestor_id);
					$crumbs[] = [
						'text' => $ancestor->name,
						'url' => get_category_link($ancestor_id),
					];
				}
				// Category của bài viết
				$crumbs[] = [
					'text' => $cat->name,
					'url' => get_category_link($cat->term_id),
				];
			}
			// Bài viết hiện tại (cuối cùng)
			$crumbs[] = [
				'text' => get_the_title(),
				'url' => '',
			];
		} elseif (is_page()) {
			global $post;
			if ($post->post_parent) {
				$ancestors = array_reverse(get_post_ancestors($post->ID));
				foreach ($ancestors as $ancestor_id) {
					$crumbs[] = [
						'text' => get_the_title($ancestor_id),
						'url' => get_permalink($ancestor_id),
					];
				}
			}
			$crumbs[] = [
				'text' => get_the_title(),
				'url' => '',
			];
		} elseif (is_search()) {
			$crumbs[] = [
				'text' => 'Bạn đã tìm kiếm: ' . esc_html(get_search_query()),
				'url' => '',
			];
		} elseif (is_tag()) {
			$crumbs[] = [
				'text' => single_tag_title('', false),
				'url' => '',
			];
		} elseif (is_author()) {
			$crumbs[] = [
				'text' => 'Tác giả: ' . get_the_author(),
				'url' => '',
			];
		} elseif (is_day()) {
			$crumbs[] = [
				'text' => 'Lưu trữ: ' . get_the_date(),
				'url' => '',
			];
		} elseif (is_month()) {
			$crumbs[] = [
				'text' => 'Lưu trữ: ' . get_the_date('F Y'),
				'url' => '',
			];
		} elseif (is_year()) {
			$crumbs[] = [
				'text' => 'Lưu trữ: ' . get_the_date('Y'),
				'url' => '',
			];
		} elseif (is_archive()) {
			$crumbs[] = [
				'text' => get_the_archive_title(),
				'url' => '',
			];
		} elseif (is_404()) {
			$crumbs[] = [
				'text' => 'Lỗi 404 - Không tìm thấy trang',
				'url' => '',
			];
		}

		// Build HTML giống Yoast SEO
		$total = count($crumbs);
		$links = [];
		foreach ($crumbs as $index => $crumb) {
			$text = trim($crumb['text']);
			if (empty($text))
				continue;

			if ($index < ($total - 1) && !empty($crumb['url'])) {
				// Crumb có link
				$links[] = '<span><a href="' . esc_url($crumb['url']) . '">' . esc_html($text) . '</a></span>';
			} elseif ($index === ($total - 1)) {
				// Crumb cuối cùng - bold, không link
				$links[] = '<span class="breadcrumb_last" aria-current="page">' . esc_html($text) . '</span>';
			} else {
				// Crumb không có link
				$links[] = '<span>' . esc_html($text) . '</span>';
			}
		}

		return '<span>' . implode($separator, $links) . '</span>';
	});
}

// Add Breadcrumb before blog content
add_action('flatsome_before_blog', function () {
	if (is_single()) {
		echo '<div class="custom-breadcrumb-bar"><div class="container">';
		echo "<div class='custom-breadcrumb-container'>";
		echo do_shortcode('[wpseo_breadcrumb]');
		echo "</div>";
		echo '</div></div>';
	}
});

// Giải pháp dùng Javascript để ép Slider nhảy từng cái một (slidesToScroll: 1)
add_action('wp_footer', function () {
	?>
	<div class="cs-drawer-overlay" id="csDrawerOverlay"></div>
	<div class="cs-drawer-panel" id="csDrawerPanel" role="dialog">
		<div class="cs-drawer-header">
			<div></div>
			<button class="cs-drawer-close" id="csDrawerClose"><i class="fa fa-times"></i></button>
		</div>
		<div class="cs-drawer-body">
			<?php echo do_shortcode('[block id="mo-ta-drawer"]'); ?>
		</div>
	</div>

	<script>
		jQuery(document).ready(function ($) {
			// Poll until Flickity is ready on .custom-slider-brand, then fix groupCells
			var _brandFixInterval = setInterval(function () {
				var $brandSlider = $('.custom-slider-brand');
				if (!$brandSlider.length) return;
				var flkty = $brandSlider.data('flickity');
				if (!flkty) return;

				clearInterval(_brandFixInterval);

				// Destroy (ignore DOM errors) then reinit with groupCells: false
				try { $brandSlider.flickity('destroy'); } catch (e) { }
				$brandSlider.flickity({
					groupCells: false,
					wrapAround: true,
					autoPlay: flkty.options.autoPlay || false,
					prevNextButtons: true,
					pageDots: flkty.options.pageDots !== undefined ? flkty.options.pageDots : false,
					cellAlign: flkty.options.cellAlign || 'left',
					contain: flkty.options.contain || false
				});
			}, 100);



			setTimeout(function () {
				var $li = $('.header-search-dropdown');
				if (!$li.length) return;
				var $a = $li.find('> a');
				var $dropdown = $li.find('.nav-dropdown');
				$li.off();
				$a.off();
				$a.on('click', function (e) {
					e.preventDefault();
					e.stopImmediatePropagation();
					$dropdown.toggleClass('is-active');
					if ($dropdown.hasClass('is-active')) {
						setTimeout(function () {
							$dropdown.find('input[type="search"]').focus();
						}, 100);
					}
				});
				$(document).off('click.searchClose').on('click.searchClose', function (e) {
					if (!$(e.target).closest('.header-search-dropdown').length) {
						$dropdown.removeClass('is-active');
					}
				});
				$dropdown.find('.live-search-results').remove();
				$dropdown.find('.ux-search-box').removeAttr('data-ux-search data-search-delay').removeClass('ux-search-box');
				var $input = $dropdown.find('input[type="search"]').first();
				$input.off();
				$input.attr('placeholder', 'Tìm kiếm ...');
				$input.on('keyup keydown input', function (e) {
					e.stopImmediatePropagation();
				});
				// Giữ nguyên language prefix (/vi/, /en/...) khi submit search
				$dropdown.find('form').off('submit.searchFix').on('submit.searchFix', function (e) {
					e.preventDefault();
					var q = $input.val().trim();
					if (q) {
						var langPrefix = '';
						var m = window.location.pathname.match(/^(\/[a-z]{2}\/)/);
						if (m) langPrefix = m[1];
						window.location.href = langPrefix + '?s=' + encodeURIComponent(q);
					}
				});
			}, 500);


			var $overlay = $('#csDrawerOverlay');
			var $panel = $('#csDrawerPanel');

			function openDrawer() {
				$overlay.addClass('is-open');
				$panel.addClass('is-open');
				$('body').addClass('cs-drawer-active').css('overflow', 'hidden');
			}

			function closeDrawer() {
				$overlay.removeClass('is-open');
				$panel.removeClass('is-open');
				$('body').removeClass('cs-drawer-active').css('overflow', '');
			}

			$(document).off('click', '.custom-drawer').on('click', '.custom-drawer', function (e) {
				e.preventDefault();
				e.stopPropagation();
				openDrawer();
			});

			$(document).on('click', '#csDrawerClose, #csDrawerOverlay', function () {
				closeDrawer();
			});

			$(document).on('keydown', function (e) {
				if (e.key === 'Escape') closeDrawer();
			});
		});
	</script>
	<?php
}, 99);

// Override blog_posts shortcode
require get_stylesheet_directory() . '/inc/shortcodes/blog_posts.php';
require get_stylesheet_directory() . '/inc/shortcodes/branch_map.php';

function override_flatsome_shortcodes()
{
	remove_shortcode('blog_posts');
	add_shortcode('blog_posts', 'custom_shortcode_latest_from_blog');
}
add_action('init', 'override_flatsome_shortcodes', 20);


// Cấu hình slider nhảy từng item một (Single Slide)
add_filter('flatsome_flickity_options', function ($options, $atts) {
	// Ép slider nhảy 1 item mỗi lần auto-slide thay vì nhảy cả group
	$options['groupCells'] = false;

	// Bạn cũng có thể chỉnh tốc độ autoslide tại đây nếu muốn (ví dụ 3 giây)
	// $options['autoPlay'] = 3000; 

	return $options;
}, 10, 2);



//related post
function flatsome_related_posts_by_category($atts)
{
	if (!is_single())
		return '<style>#block-2 { display: none !important; }</style>'; // Ẩn nếu không phải trang bài viết

	$categories = get_the_category(get_the_ID());
	if ($categories) {
		$category_ids = array();
		foreach ($categories as $individual_category)
			$category_ids[] = $individual_category->term_id;

		$args = array(
			'category__in' => $category_ids,
			'post__not_in' => array(get_the_ID()), // Không hiện chính bài đang xem
			'posts_per_page' => 5, // Số lượng bài muốn hiện
		);

		$query = new WP_Query($args);
		if ($query->have_posts()) {
			$header_html = '<h3 id="cs-category" class="wow fadeInRight" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInRight; width: 100%; display: flex; align-items: center; border: 1px solid #ebebeb; margin-bottom: 20px; font-size: 18px; text-transform: uppercase;">
								<div style="margin-right: 10px; line-height: 1;"><img src="/wp-content/uploads/2026/04/newspaper.png" alt="" style="max-height: 40px;"></div> 
								<span style="font-weight: bold; color: #1e5c94;">Related news</span>
							</h3>';

			$output = $header_html . '<div class="custom-related-posts-sidebar">';
			while ($query->have_posts()) {
				$query->the_post();
				$output .= '<a href="' . get_permalink() . '" class="related-post-item">';

				// Ảnh đại diện
				if (has_post_thumbnail()) {
					$output .= '<div class="related-post-image">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</div>';
				}

				$output .= '<div class="related-post-info">';
				// Tiêu đề
				$output .= '<h5>' . get_the_title() . '</h5>';
				// Ngày tháng với icon đỏ
				$output .= '<span class="related-post-date"><i class="fa fa-calendar"></i> ' . get_the_date('d-m-Y') . '</span>';
				$output .= '</div>'; // .related-post-info

				$output .= '</a>'; // .related-post-item
			}
			$output .= '</div>';
			wp_reset_postdata();
			return $output;
		}
	}

	// Nếu không có bài viết nào hoặc không có category, ẩn block-2
	return '<style>#block-2 { display: none !important; }</style>';
}
add_shortcode('related_posts_sidebar', 'flatsome_related_posts_by_category');

// Filter search results to only include posts
add_filter('pre_get_posts', function ($query) {
	if ($query->is_search && !is_admin() && $query->is_main_query()) {
		$query->set('post_type', 'post');
	}
	return $query;
});

// Fix: TranslatePress intercept /vi/ khiến WordPress không nhận ?s= vào query_vars.
// Dùng parse_request (chạy sớm hơn) để force inject s vào query — giữ nguyên tiếng Việt.
add_action('parse_request', function ($wp) {
	if (is_admin())
		return;

	if (isset($_GET['s']) && empty($wp->query_vars['s'])) {
		$wp->query_vars['s'] = sanitize_text_field($_GET['s']);
	}
});

// Ngăn TranslatePress ghi đè kết quả tìm kiếm (xóa biến 's' và thay bằng post__in) trong ngữ cảnh tiếng Việt
add_action('pre_get_posts', function ($query) {
	if ($query->is_search && $query->is_main_query() && !is_admin()) {
		if (class_exists('TRP_Translate_Press')) {
			$trp = TRP_Translate_Press::get_trp_instance();
			$search = $trp->get_component('search');
			if ($search) {
				remove_action('pre_get_posts', [$search, 'trp_search_filter'], 99999999);
			}
		}
	}
}, 99999998);

// Ghi đè hàm phân trang của Flatsome để thu gọn số lượng trang hiển thị
if (!function_exists('flatsome_posts_pagination')) {
	function flatsome_posts_pagination()
	{
		$prev_arrow = is_rtl() ? get_flatsome_icon('icon-angle-right') : get_flatsome_icon('icon-angle-left');
		$next_arrow = is_rtl() ? get_flatsome_icon('icon-angle-left') : get_flatsome_icon('icon-angle-right');

		global $wp_query;
		$total = $wp_query->max_num_pages;
		$big = 999999999;
		if ($total > 1) {
			if (!$current_page = get_query_var('paged'))
				$current_page = 1;
			if (get_option('permalink_structure')) {
				$format = 'page/%#%/';
			} else {
				$format = '&paged=%#%';
			}
			$pages = paginate_links(array(
				'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format' => $format,
				'current' => max(1, get_query_var('paged')),
				'total' => $total,
				'mid_size' => 1, // Đã sửa thành 1 để thu gọn
				'type' => 'array',
				'prev_text' => $prev_arrow,
				'next_text' => $next_arrow,
			));

			if (is_array($pages)) {
				echo '<ul class="page-numbers nav-pagination links text-center">';
				foreach ($pages as $page) {
					$page = str_replace("page-numbers", "page-number", $page);
					$page = str_replace('<a class="next page-number', '<a aria-label="' . esc_attr__('Next', 'flatsome') . '" class="next page-number', $page);
					$page = str_replace('<a class="prev page-number', '<a aria-label="' . esc_attr__('Previous', 'flatsome') . '" class="prev page-number', $page);
					echo "<li>$page</li>";
				}
				echo '</ul>';
			}
		}
	}
}
