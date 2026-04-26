<?php

function thami_branch_map_shortcode()
{
    $branches = [
        [
            'name' => 'HO CHI MINH (HEAD OFFICE)',
            'address' => '25 - 25A, Street 81, Tan Hung Ward, Hochiminh City',
            'tel' => '+84.28.3775 0888 (50 lines)',
            'fax' => '+84.28.3775 0666',
            'hotline' => '+84.901 139 019',
            'email' => 'info@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1315.0667335759167!2d106.70640535537073!3d10.740122118719666!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f9da32c861f%3A0x8191a51dcc4ea60a!2zQ8O0bmcgVHkgQ-G7lSBQaOG6p24gVGjDoWkgTWluaA!5e0!3m2!1svi!2s!4v1515741190450'
        ],
        [
            'name' => 'AIR (HO CHI MINH)',
            'address' => '38 Truong Son Str., Tan Son Hoa Ward, Ho Chi Minh City, Vietnam',
            'tel' => '+84.28.3547 1438',
            'fax' => '+84.28.3547 1439',
            'email' => 'operation@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1959.51065226045!2d106.66401065793363!3d10.809679998074742!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317529232f1018bb%3A0x5dac9b24e4ab6b2!2zMzggVHLGsOG7nW5nIFPGoW4sIFBoxrDhu51uZyAyLCBUw6JuIELDrG5oLCBI4buTIENow60gTWluaCwgVmlldG5hbQ!5e0!3m2!1sen!2sus!4v1510886169841'
        ],
        [
            'name' => 'AIR (HA NOI)',
            'address' => '5th Floor, 25T1 Bldg, Lot A1/D21, Dong Nam Urban Area, Tran Duy Hung St, Yen Hoa Ward, Ha Noi City',
            'tel' => '+84 899 939898',
            'email' => 'haninfo@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d658.4282889945181!2d105.80060393080286!3d21.00734895020561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135aca143131561%3A0x5d47295f81445f37!2zMjVUMSBUcnVuZyBIw7JhIE5ow6JuIENow61uaA!5e0!3m2!1sen!2s!4v1769670696174!5m2!1sen!2s'
        ],
        [
            'name' => 'HA NOI',
            'address' => '5th Floor, 25T1 Bldg, Lot A1/D21, Dong Nam Urban Area, Tran Duy Hung St, Yen Hoa Ward, Ha Noi City',
            'tel' => '+84 899 939898',
            'email' => 'haninfo@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d658.4282889945181!2d105.80060393080286!3d21.00734895020561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135aca143131561%3A0x5d47295f81445f37!2zMjVUMSBUcnVuZyBIw7JhIE5ow6JuIENow61uaA!5e0!3m2!1sen!2s!4v1769670696174!5m2!1sen!2s'
        ],
        [
            'name' => 'HAI PHONG',
            'address' => 'Room 501, 5th Floor, Vietsun Building, 371 - 373 - 375 Le Thanh Tong St, Ngo Quyen Ward, Hai Phong City',
            'tel' => '+84.225.3552 339',
            'fax' => '+84.225.3552 340',
            'email' => 'haiphong@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d50159.84376166963!2d106.64825901989548!3d20.865894859489597!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314a7b541457569d%3A0x99efc7e5ac0a4f85!2sVIETSUN%20HAIPHONG%20REP.!5e0!3m2!1svi!2s!4v1741248592507!5m2!1svi!2s'
        ],
        [
            'name' => 'VINH',
            'address' => '10 Truong Thi Str., Truong Vinh Ward, Nghe An',
            'tel' => '+84.238.8602 113',
            'fax' => '+84.238.8602 333',
            'email' => 'vinh.office@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3779.862704858865!2d105.69236100000002!3d18.670156!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3139cdd71aa88d4d%3A0xa69edfa576e470e8!2zMTAgVHLGsOG7nW5nIFRoaSwgVHAuIFZpbmgsIE5naOG7hyBBbiwgVmlldG5hbQ!5e0!3m2!1sen!2sus!4v1510885368853'
        ],
        [
            'name' => 'DA NANG',
            'address' => '7th Floor, Quoc Bao Building, 23 Truong Thi 1 Street, Hoa Cuong Ward, Da Nang City',
            'tel' => '+84.236.3843 606/07',
            'fax' => '+84.236.3843 608',
            'email' => 'danang@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d958.5244820165623!2d108.20373536955823!3d16.060407287801127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x314219b2257fe519%3A0xd7f869268b399ea9!2zMjMgVHLGsOG7nW5nIFRoaSAxLCBIw7JhIFRodeG6rW4gVMOieSwgSOG6o2kgQ2jDonUsIMSQw6AgTuG6tW5nIDU1MDAwMCwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1718178667685'
        ],
        [
            'name' => 'DONG NAI',
            'address' => 'Room 11, 8th Floor, Sonadezi Tower, Street 1, Bien Hoa 1 IZ, Tran Bien Ward, Dong Nai',
            'tel' => '+84 251 6293290/91/92',
            'fax' => '+84 251 6293294',
            'email' => 'dongnai@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15670.959342874588!2d106.848628!3d10.907366!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x3446db8de24970ce!2sSonadezi+Building+Buildings!5e0!3m2!1sen!2sus!4v1510885999513'
        ],
        [
            'name' => 'BINH DUONG',
            'address' => 'H12, NB-D1 Str, Eco Xuan Lai Thieu Area, Lai Thieu Ward, Ho Chi Minh City',
            'tel' => '+84.274.3813.894/ 895',
            'email' => 'binhduong@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31338.99817730705!2d106.68474889476666!3d10.935036897009677!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3174d797dfc71b5d%3A0x6a1ec8d5eae0c656!2zS2h1IMSRw7QgdGjhu4sgRWNvWHXDom4!5e0!3m2!1svi!2s!4v1743476362320'
        ],
        [
            'name' => 'CAN THO',
            'address' => '5th Floor, VCCI Bldg., 12 Hoa Binh Str., Ninh Kieu Ward, Can Tho',
            'tel' => '+84.292.3818 141/142',
            'fax' => '+84.292.3818 143',
            'email' => 'cantho@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7857.572446582163!2d105.785256!3d10.034492!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xcb61b2f2a5002ec8!2sC%C3%B4ng+Ty+Cp+Th%C3%A1i+Minh+-+Cn!5e0!3m2!1sen!2sus!4v1510886362531'
        ],
        [
            'name' => 'BAC LIEU',
            'address' => '158/5 Tran Huynh St, Bac Lieu Ward, Ca Mau',
            'tel' => '+84.291.3678 178',
            'fax' => '+84.291.3678 179',
            'email' => 'baclieu@thamico.com',
            'map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3937.4429372157474!2d105.71905681440461!3d9.293962493337325!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a1096e545d3b61%3A0xefb71e760c0a6e37!2zMTU4IFRy4bqnbiBIdeG7s25oLCBQaMaw4budbmcgNywgVHAuIELhuqFjIExpw6p1LCBC4bqhYyBMacOqdSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1510886563191'
        ]
    ];

    ob_start();
    ?>
    <div class="thami-branch-section">
        <div class="branch-tabs-container">
            <div class="custom-branch-slider row row-slider slider slider-nav-circle slider-nav-push slider-nav-large large-columns-7 medium-columns-4 small-columns-2"
                data-flickity-options='{ "cellAlign": "left", "draggable": false, "imagesLoaded": true, "lazyLoad": 1, "freeScroll": false, "wrapAround": true, "autoPlay": false, "prevNextButtons": true, "contain": false, "adaptiveHeight": true, "percentPosition": true, "pageDots": false, "groupCells": false }'>
                <?php foreach ($branches as $index => $branch): ?>
                    <div class="col branch-tab-cell <?php echo $index === 0 ? 'is-active' : ''; ?>"
                        data-index="<?php echo $index; ?>">
                        <div class="col-inner">
                            <div class="branch-tab-item">
                                <span><?php echo esc_html($branch['name']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="branch-contents">
            <?php foreach ($branches as $index => $branch): ?>
                <div class="branch-content-item <?php echo $index === 0 ? 'is-active' : ''; ?>"
                    id="branch-content-<?php echo $index; ?>">
                    <div class="branch-info-bar">
                        <div class=" text-center">
                            <p>
                                <span><i class="fa fa-map-marker"></i> <?php echo esc_html($branch['address']); ?></span>
                                <?php if (!empty($branch['tel'])): ?>
                                    <span class="sep">-</span> <span><i class="fa fa-phone"></i> Tel:
                                        <?php echo esc_html($branch['tel']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($branch['fax'])): ?>
                                    <span class="sep">-</span> <span><i class="fa fa-fax"></i> Fax:
                                        <?php echo esc_html($branch['fax']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($branch['hotline'])): ?>
                                    <span class="sep">-</span> <span>Hotline: <?php echo esc_html($branch['hotline']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($branch['email'])): ?>
                                    <span class="sep">-</span> <span><i class="fa fa-envelope"></i> email:
                                        <?php echo esc_html($branch['email']); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="branch-map-container">
                        <iframe src="<?php echo esc_url($branch['map_url']); ?>" width="100%" height="450" style="border:0;"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            var $slider = $('.branch-tabs-container .custom-branch-slider');

            var isClickingTab = false;

            // Hàm chuyển đổi tab content
            function switchTab(index) {
                // Cập nhật trạng thái active cho tab
                $('.branch-tab-cell').removeClass('is-active');
                var $targetTab = $('.branch-tab-cell[data-index="' + index + '"]');
                $targetTab.addClass('is-active');

                // Cập nhật nội dung bản đồ
                $('.branch-content-item').removeClass('is-active');
                $('#branch-content-' + index).addClass('is-active');
            }

            // Chuyển content khi click vào tab
            $slider.on('click', '.branch-tab-cell', function () {
                var index = $(this).data('index');
                switchTab(index);
            });

            // Cấy trực tiếp thẻ <i> vào bên trong các nút điều hướng (Dùng thẻ HTML xịn theo ý của bạn)
            function replaceSliderIcons() {
                var $prevBtn = $slider.find('.flickity-prev-next-button.previous');
                var $nextBtn = $slider.find('.flickity-prev-next-button.next');

                if ($prevBtn.length && $nextBtn.length) {
                    // Tránh thêm nhiều lần
                    if ($prevBtn.find('i').length === 0) {
                        $prevBtn.append('<i class="fa fa-fw fa-angle-left"></i>');
                    }
                    if ($nextBtn.find('i').length === 0) {
                        $nextBtn.append('<i class="fa fa-fw fa-angle-right"></i>');
                    }
                } else {
                    // Nếu slider chưa khởi tạo kịp, thử lại sau 50ms
                    setTimeout(replaceSliderIcons, 50);
                }
            }

            // Kích hoạt việc cấy Icon
            replaceSliderIcons();

            // Đồng bộ nội dung khi Mũi Tên Trái Phải được click (Chỉ khi Flickity tự kéo)
            $slider.on('settle.flickity', function () {
                var flkty = $slider.data('flickity');
                if (flkty) {
                    var $activeTab = $('.branch-tab-cell.is-active');
                    if ($activeTab.length === 0) return;

                    var viewport = flkty.viewport;
                    var viewportRect = viewport.getBoundingClientRect();
                    var activeRect = $activeTab[0].getBoundingClientRect();

                    // Kiểm tra xem tab đang active có bị trượt ra ngoài viewport không
                    // Lấy giao điểm để biết chính xác nó đang nằm trong
                    var isVisible = (activeRect.right > viewportRect.left && activeRect.left < viewportRect.right);

                    if (!isVisible) {
                        // Nếu đã khuất khỏi tầm nhìn, lập tức nhảy sang cái đang được Slider focus
                        var $selected = $(flkty.selectedElement);
                        var index = $selected.data('index');
                        switchTab(index);
                    }
                }
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('thami_branches', 'thami_branch_map_shortcode');
