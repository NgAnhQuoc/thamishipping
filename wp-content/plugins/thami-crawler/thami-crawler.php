<?php
/**
 * Plugin Name: Thami Books Crawler
 * Description: Crawl books from thamishipping.com
 * Version: 1.0
 * Author: Antigravity
 */

if (!defined('ABSPATH'))
    exit;

class Thami_Books_Crawler
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('wp_ajax_thami_start_crawl', [$this, 'ajax_start_crawl']);
    }

    public function add_menu()
    {
        add_menu_page(
            'Thami Crawler',
            'Thami Crawler',
            'manage_options',
            'thami-crawler',
            [$this, 'render_admin_page'],
            'dashicons-download',
            26
        );
    }

    public function render_admin_page()
    {
        $categories = get_categories(['hide_empty' => 0]);
        ?>
        <div class="wrap">
            <h1>Thami Books Crawler</h1>
            <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; max-width: 800px;">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="crawl-url">URL nguồn (Trang danh sách)</label></th>
                        <td>
                            <input type="url" id="crawl-url" class="regular-text"
                                value="https://www.thamishipping.com/blogs/sach-hay"
                                placeholder="https://example.com/blogs/news">
                            <p class="description">Đường dẫn trang danh sách bài viết cần crawl.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="target-category">Chọn Chuyên mục</label></th>
                        <td>
                            <select id="target-category">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat->term_id; ?>" <?php selected($cat->name, 'Sách hay'); ?>>
                                        <?php echo esc_html($cat->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <button id="start-crawl" class="button button-primary">Bắt đầu Crawl</button>
                    <button id="clear-log" class="button">Xóa nhật ký</button>
                </p>
            </div>

            <div id="crawl-results"
                style="margin-top: 20px; padding: 10px; background: #fff; border: 1px solid #ccc; height: 500px; overflow-y: auto; font-family: monospace; max-width: 800px;">
            </div>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                $('#clear-log').on('click', function () {
                    $('#crawl-results').empty();
                });

                $('#start-crawl').on('click', function () {
                    var $btn = $(this);
                    var crawlUrl = $('#crawl-url').val();
                    var catId = $('#target-category').val();

                    if (!crawlUrl) {
                        alert('Vui lòng nhập URL nguồn.');
                        return;
                    }

                    $btn.prop('disabled', true).text('Đang crawl...');
                    $('#crawl-results').append('<p><strong>[SYSTEM]</strong> Bắt đầu quá trình crawl từ: ' + crawlUrl + '</p>');

                    function crawl(page = 1) {
                        $.post(ajaxurl, {
                            action: 'thami_start_crawl',
                            page: page,
                            crawl_url: crawlUrl,
                            category_id: catId
                        }, function (response) {
                            if (response.success) {
                                $('#crawl-results').append(response.data.message);
                                $('#crawl-results').scrollTop($('#crawl-results')[0].scrollHeight);

                                if (response.data.has_next) {
                                    $('#crawl-results').append('<p><strong>[SYSTEM]</strong> Đang chuyển sang trang tiếp theo...</p>');
                                    crawl(page + 1);
                                } else {
                                    $btn.prop('disabled', false).text('Bắt đầu Crawl');
                                    $('#crawl-results').append('<p><strong>[SYSTEM] Hoàn thành!</strong></p>');
                                }
                            } else {
                                $('#crawl-results').append('<p style="color:red"><strong>[ERROR]</strong> Lỗi: ' + response.data + '</p>');
                                $btn.prop('disabled', false).text('Bắt đầu Crawl');
                            }
                        }).fail(function () {
                            $('#crawl-results').append('<p style="color:red"><strong>[ERROR]</strong> Lỗi kết nối server.</p>');
                            $btn.prop('disabled', false).text('Bắt đầu Crawl');
                        });
                    }

                    crawl(1);
                });
            });
        </script>
        <?php
    }

    public function ajax_start_crawl()
    {
        $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $base_url = isset($_POST['crawl_url']) ? esc_url_raw($_POST['crawl_url']) : "https://www.thamishipping.com/blogs/sach-hay";
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;

        $url = $paged > 1 ? add_query_arg('page', $paged, $base_url) : $base_url;

        $response = wp_remote_get($url, ['timeout' => 30]);
        if (is_wp_error($response)) {
            wp_send_json_error('Không thể kết nối đến URL: ' . $response->get_error_message());
        }

        $html = wp_remote_retrieve_body($response);
        if (empty($html)) {
            wp_send_json_error('Trang trống hoặc không thể tải nội dung.');
        }

        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($dom);

        // Lấy tất cả các khối bài viết
        $nodes = $xpath->query("//div[contains(@class, 'row')]//div[contains(@class, 'info')]//h5/a[contains(@class, 'transition')]");

        $posts_to_crawl = [];
        foreach ($nodes as $node) {
            $href = $node->getAttribute('href');
            if (empty($href))
                continue;

            if (strpos($href, 'http') === false) {
                $parse = parse_url($base_url);
                $domain = $parse['scheme'] . '://' . $parse['host'];
                $href = $domain . $href;
            }

            // Khởi tạo các thông tin cần lấy thêm từ danh sách
            $img_url = '';
            $post_date = '';

            $parent_row = $xpath->query("ancestor::div[contains(@class, 'row')][1]", $node)->item(0);
            if ($parent_row) {
                // Lấy ảnh thumb
                $img_node = $xpath->query(".//div[contains(@class, 'img')]//img[not(contains(@src, 'plus.png'))]", $parent_row)->item(0);
                if ($img_node) {
                    $img_url = $img_node->getAttribute('src');
                    if (strpos($img_url, 'http') === false) {
                        $parse = parse_url($base_url);
                        $domain = $parse['scheme'] . '://' . $parse['host'];
                        $img_url = $domain . $img_url;
                    }
                }

                // Lấy ngày đăng (<p id="date">)
                $date_node = $xpath->query(".//p[@id='date']", $parent_row)->item(0);
                if ($date_node) {
                    $post_date = trim($date_node->nodeValue);
                    // Format thường là DD-MM-YYYY hoặc DD/MM/YYYY
                    // Chuyển sang YYYY-MM-DD để WordPress hiểu
                    if (preg_match('/(\d{1,2})[-|\/](\d{1,2})[-|\/](\d{4})/', $post_date, $matches)) {
                        $post_date = $matches[3] . '-' . $matches[2] . '-' . $matches[1] . ' 12:00:00';
                    }
                }
            }

            if (strpos($href, '/blogs/') !== false && strpos($href, '?') === false) {
                $posts_to_crawl[] = [
                    'url' => $href,
                    'thumb' => $img_url,
                    'date' => $post_date
                ];
            }
        }

        // Loại bỏ trùng lặp URL
        $temp_urls = [];
        $unique_posts = [];
        foreach ($posts_to_crawl as $p) {
            if (!in_array($p['url'], $temp_urls)) {
                $temp_urls[] = $p['url'];
                $unique_posts[] = $p;
            }
        }

        if (empty($unique_posts)) {
            wp_send_json_success([
                'message' => "<strong>[PAGE $paged]</strong> Không tìm thấy bài viết nào.<br>",
                'has_next' => false
            ]);
        }

        $message = "<strong>[PAGE $paged]</strong> Tìm thấy " . count($unique_posts) . " bài viết.<br>";

        // Nếu không chọn category ID, thử lấy hoặc tạo chuyên mục mặc định
        if (!$category_id) {
            $category_id = $this->get_or_create_category('Sách hay');
        }

        foreach ($unique_posts as $post_data) {
            $message .= $this->process_single_post($post_data['url'], $category_id, $post_data['thumb'], $post_data['date']);
        }

        // Kiểm tra phân trang (ul.pagination li a[rel="next"])
        $has_next = false;
        $next_nodes = $xpath->query("//ul[contains(@class, 'pagination')]//a[@rel='next']");
        if ($next_nodes->length > 0) {
            $has_next = true;
        }

        wp_send_json_success([
            'message' => $message,
            'has_next' => $has_next
        ]);
    }

    private function get_or_create_category($name)
    {
        $category = get_term_by('name', $name, 'category');
        if ($category) {
            return $category->term_id;
        }
        $new_cat = wp_insert_term($name, 'category');
        if (is_wp_error($new_cat)) {
            return 0;
        }
        return $new_cat['term_id'];
    }

    private function process_single_post($url, $category_id, $thumb_url = '', $post_date = '')
    {
        $post_name = sanitize_title(basename($url));

        // Kiểm tra bài viết đã tồn tại chưa
        $existing = get_posts([
            'name' => $post_name,
            'post_type' => 'post',
            'post_status' => 'any',
            'numberposts' => 1
        ]);

        if ($existing) {
            $existing_post = $existing[0];
            // Cập nhật ngày đăng nếu có
            if (!empty($post_date)) {
                wp_update_post([
                    'ID' => $existing_post->ID,
                    'post_date' => $post_date,
                    'post_date_gmt' => get_gmt_from_date($post_date)
                ]);
                return "--- Cập nhật ngày: <span style='color:#2196F3'><strong>" . $existing_post->post_title . "</strong></span> ($post_date)<br>";
            }
            return "--- Bỏ qua: <span style='color:#777'>" . basename($url) . "</span> (Đã tồn tại)<br>";
        }

        $response = wp_remote_get($url, ['timeout' => 30, 'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36']);
        if (is_wp_error($response)) {
            return "--- <span style='color:red'>Lỗi: " . basename($url) . " (Không thể truy cập)</span><br>";
        }

        $html = wp_remote_retrieve_body($response);
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $xpath = new DOMXPath($dom);

        // Lấy Tiêu đề (h1#title)
        $title_node = $xpath->query("//h1[@id='title']")->item(0);
        if (!$title_node)
            $title_node = $xpath->query("//h1")->item(0);

        $title = $title_node ? trim($title_node->nodeValue) : '';
        $title = str_replace([' - THAMI SHIPPING & AIRFREIGHT CORP.', ' - THAMI SHIPPING'], '', $title);

        if (empty($title))
            return "--- <span style='color:red'>Lỗi: " . basename($url) . " (Không tìm thấy tiêu đề)</span><br>";

        // Lấy Nội dung (.content_news)
        $content_node = $xpath->query("//div[contains(@class, 'content_news')]")->item(0);
        if (!$content_node)
            $content_node = $xpath->query("//div[contains(@class, 'article-content')]")->item(0);
        if (!$content_node)
            $content_node = $xpath->query("//div[contains(@class, 'content-page')]")->item(0);

        if (!$content_node) {
            return "--- <span style='color:orange'>Lỗi: $title (Không tìm thấy khối nội dung)</span><br>";
        }

        // Tải ảnh trong nội dung về máy local
        $images = $content_node->getElementsByTagName('img');
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (empty($src))
                continue;

            if (strpos($src, 'http') === false) {
                $src = (strpos($src, '//') === 0) ? 'https:' . $src : 'https://www.thamishipping.com' . $src;
            }

            $local_url = $this->upload_image_from_url($src);
            if ($local_url) {
                $img->setAttribute('src', $local_url);
            }
        }

        // Lấy HTML nội dung đã xử lý ảnh
        $content = $dom->saveHTML($content_node);

        // Lấy Ảnh đại diện (Featured Image)
        // Ưu tiên ảnh $thumb_url lấy từ danh sách bài viết
        $featured_image_url = $thumb_url;

        // Nếu không có ảnh từ danh sách, thử lấy từ meta og:image
        if (empty($featured_image_url)) {
            $meta_og_image = $xpath->query("//meta[@property='og:image']/@content")->item(0);
            if ($meta_og_image) {
                $featured_image_url = $meta_og_image->nodeValue;
            }
        }

        // Cuối cùng thử lấy ảnh đầu tiên trong bài
        if (empty($featured_image_url) && $images->length > 0) {
            $featured_image_url = $images->item(0)->getAttribute('src');
        }

        // Tạo bài viết mới
        $args = [
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_category' => [$category_id],
            'post_name' => $post_name,
            'post_type' => 'post',
            'comment_status' => 'closed',
            'ping_status' => 'closed'
        ];

        if (!empty($post_date)) {
            $args['post_date'] = $post_date;
            $args['post_date_gmt'] = get_gmt_from_date($post_date);
        }

        $new_post_id = wp_insert_post($args);

        if ($new_post_id && !is_wp_error($new_post_id)) {
            if (!empty($featured_image_url)) {
                $this->set_featured_image($new_post_id, $featured_image_url);
            }
            return "--- <span style='color:green'>Thành công: <strong>$title</strong></span> ($post_date)<br>";
        }

        return "--- <span style='color:red'>Lỗi: $title (Không thể lưu vào database)</span><br>";
    }

    private function upload_image_from_url($url)
    {
        if (empty($url))
            return false;
        if (strpos($url, '//') === 0)
            $url = 'https:' . $url;

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $tmp = download_url($url);
        if (is_wp_error($tmp))
            return false;

        $file_array = [
            'name' => basename(strtok($url, '?')),
            'tmp_name' => $tmp
        ];

        $id = media_handle_sideload($file_array, 0);
        if (is_wp_error($id)) {
            @unlink($tmp);
            return false;
        }

        return wp_get_attachment_url($id);
    }

    private function set_featured_image($post_id, $url)
    {
        if (empty($url))
            return false;
        if (strpos($url, '//') === 0)
            $url = 'https:' . $url;

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $tmp = download_url($url);
        if (is_wp_error($tmp))
            return false;

        $file_array = [
            'name' => basename(strtok($url, '?')),
            'tmp_name' => $tmp
        ];

        $id = media_handle_sideload($file_array, $post_id);
        if (is_wp_error($id)) {
            @unlink($tmp);
            return false;
        }

        return set_post_thumbnail($post_id, $id);
    }
}

new Thami_Books_Crawler();
