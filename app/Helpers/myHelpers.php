<?php

use Carbon\Carbon;

    //  m/d/Y -> Y-m-d
    if (!function_exists('formatDateToSql')) {
        function formatDateToSql($date) {
            $formattedDate = Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
            return $formattedDate;
        }
    }

    //   Y-m-d H:i:s -> d/m/Y H:i
    if (!function_exists('formatDateTimeFromSql')) {
        function formatDateTimeFromSql($datetime) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format('d/m/Y H:i');
        }
    }

    //   Y-m-d H:i:s -> d/m/Y
    if (!function_exists('formatDateFromSql')) {
        function formatDateFromSql($datetime) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format('d/m/Y');
        }
    }

    //  d/m/Y H:i -> Y-m-d H:i:s
    if (!function_exists('formatDateTimeToSql')) {
        function formatDateTimeToSql($datetime) {
            return Carbon::createFromFormat('d/m/Y H:i', $datetime)->format('Y-m-d H:i:s');
        }
    }

    if (!function_exists('createCode')) {
        function createCode($string) {
            $currentDateTime = Carbon::now();
            return $string . $currentDateTime->format('dmYHis');
        }
    }

    if (!function_exists('formatDateFromSql')) {
        function formatDateFromSql($dateString) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
            // Định dạng lại ngày giờ theo định dạng mong muốn
            return $date->format('d/m/Y H:i');
        }
    }

    if (!function_exists('goBack')) {
        function goBack() {
            return redirect()->back();
        }
    }

    if (!function_exists('convertToSlug')) {
        function convertToSlug($text) {
            $text = mb_strtolower($text, 'UTF-8');
                // Thay thế các ký tự có dấu thành không dấu
                $text = preg_replace('/[àáạảãăắằặẳẵâấầậẩẫ]/u', 'a', $text);
                $text = preg_replace('/[èéẹẻẽêếềệểễ]/u', 'e', $text);
                $text = preg_replace('/[ìíịỉĩ]/u', 'i', $text);
                $text = preg_replace('/[òóọỏõôốồộổỗơớờợởỡ]/u', 'o', $text);
                $text = preg_replace('/[ùúụủũưứừựửữ]/u', 'u', $text);
                $text = preg_replace('/[ỳýỵỷỹ]/u', 'y', $text);
                $text = preg_replace('/đ/u', 'd', $text);
                
                // Thay thế các ký tự không phải chữ cái hoặc số bằng dấu gạch ngang
                $text = preg_replace('/[^a-z0-9]/u', '-', $text);
                
                // Loại bỏ các dấu gạch ngang kéo dài
                $text = preg_replace('/-{2,}/u', '-', $text);
                
                // Loại bỏ các dấu gạch ngang ở đầu và cuối chuỗi
                $text = trim($text, '-');
                
                return $text;
        }
    }

    if (!function_exists('customizeNestedset')) {
        function customizeNestedset($objects) {
            foreach($objects as $key => $item) {
                $item->name = str_repeat('|-----', $item->level) . $item->name;
            }
            return $objects;
        }
    }

    if (!function_exists('formatNumberToSql')) {
        function formatNumberToSql($string) {
            return str_replace('.', '', $string);
        }
    }

    if (!function_exists('formatNumberFromSql')) {
        function formatNumberFromSql($string) {
            if (!is_float($string)) {
                $string = floatval($string);
            }
            return number_format($string, 0, '', '.');
        }
    }

    if (!function_exists('setupCodeFromSkuVariant')) {
        //"SAMSUNGM55-22-24" ==> "22, 24"
        function setupCodeFromSkuVariant($string) {
            // Lấy hai phần tử cuối cùng trong mảng
            $lastTwoNumbers = array_slice(explode('-', $string), -2);
            // Sắp xếp hai phần tử theo thứ tự tăng dần
            sort($lastTwoNumbers);
            // Kết hợp hai phần tử thành chuỗi với dấu phẩy
            return implode(' ', $lastTwoNumbers);
        }
    }

    if (!function_exists('setupModelRepository')) {
        function setupModelRepository($model = "") {
            return 'App\Repositories\Interfaces\\' . ucfirst($model) . 'RepositoryInterface';
        }
    }

    if (!function_exists('setupPayloadLinks')) {
        function setupPayloadLinks($links) {
            foreach($links as $key => $item) {
                $item->keyword = $item->detail['keyword'];
                $item->htmltarget = $item->detail['htmltarget'];
                $item->model = $item->detail['model'];
            }
            return $links->toTree();
        }
    }

    if (!function_exists('setupLinkForMenu')) {
        function setupLinkForMenu($links) {
            $html = '';
            if(count($links)) {
                $html = renderHtmlLink($links, $html);
            }
            return $html;
        }
    }

    if (!function_exists('renderHtmlLink')) {
        function renderHtmlLink($links) {
            $html = '';
            foreach($links as $index => $item) {
                $html .= '<li class="dd-item" data-id="'. $item->id .'" data-name="'. $item->name .'" data-canonical="'. $item->canonical .'" data-image="'. $item->image .'" data-htmlTarget="'. $item->htmltarget .'" data-model="'. $item->model .'" data-keyword="'. $item->keyword .'">
                    <span class="dd-handle">
                        <span class="label label-info"><i class="fa fa-arrows"></i></span><span class="name-item">'. $item->name .' </span>
                    </span>
                    <div class="management-submenu">
                        <span class="editItemLink btn-warning style-button"><i class="fa fa-edit"></i></span>
                        <span class="deleteItemLink btn-danger style-button"><i class="fa fa-trash"></i></span>
                        <input type="hidden" value="'. $item->canonical .'" class="dataItem" data-htmlTarget="'. $item->htmltarget .'" data-model="'. $item->model .'" data-keyword="'. $item->keyword .'">
                    </div>';
                    if(isset($item->children) && count($item->children)) {
                        $html .= '<ol class="dd-list">';
                            $html .= renderHtmlLink($item->children);
                        $html .= '</ol>';
                    }
                $html .= '</li>';
            }
            return $html;
        }
    }

    if (!function_exists('setupDetailToRenderProduct')) {
        function setupDetailToRenderProduct($payload) {
            $fields = ['product_id', 'product_variant_id', 'name', 'name_variant', 'sku', 'image'];
            $temp = [];
            foreach($payload->toArray() as $key => $val) {
                foreach($fields as $keyField) {
                    if($keyField == $key) {
                        $temp[$key] = $val;
                    }
                }
            }
            return $temp;
        }
    }

    if (!function_exists('calculateFinalTotalCost')) {
        function calculateFinalTotalCost($payload) {
            if($payload !== null) {
                $amountDiscount = 0;
                if($payload['discount_type'] !== null) {
                    $amountDiscount = $payload['discount_type'] == 'percent' ? $payload['price_total'] * $payload['discount_value'] /100 : $payload['discount_value'];
                }
                $amountFee = 0;
                if($payload['import_fee'] !== null) {
                    foreach(json_decode($payload['import_fee']) as $index => $item) {
                        $amountFee += $item->value;
                    }
                }
                return $payload['price_total'] + $amountFee - $amountDiscount;
            }else{
                return 0;
            }
        }
    }

    if (!function_exists('compareCurrentTime')) {
        function compareCurrentTime($datetimeToCompare) {
            $now = Carbon::now();
            $datetimeToCompare = Carbon::parse($datetimeToCompare);
            if ($datetimeToCompare->lt($now)) {
                return 'earlier';
            } elseif ($datetimeToCompare->gt($now)) {
                return 'later';
            } else {
                return 'same';
            }
        }
    }

    if (!function_exists('setupData')) {
        function setupData($slides) {
            $temp = [];
            foreach($slides as $index => $item) {
                $temp[$item->keyword] = $item;
            }
            return $temp;
        }
    }

    if (!function_exists('setupModelRepository')) {
        function setupModelRepository($model = '') {
            return 'App\Repositories\Interfaces\\' . ucfirst($model) . 'RepositoryInterface';

        }
    }

    if (!function_exists('calculatePriceAfterTax')) {
        function calculatePriceAfterTax($statusTax = 0, $outputTax, $price) {
            return $statusTax == 0 ? $price : ($price + $price / 100 * str_replace('VAT', '', $outputTax));
        }
    }

    if (!function_exists('calculatePriceDiscount')) {
        function calculatePriceDiscount($price, $discountType, $discountValue) {
            return $discountType == 'percent' ? ($price / 100 * $discountValue) : $discountValue;
        }
    }

    if (!function_exists('setupPromotionForProductCatalogue')) {
        function setupPromotionForProductCatalogue($promotionForPostCatalogues) {
            $data = [];
            foreach($promotionForPostCatalogues as $item) {
                $temp = [
                    'discount_value' => $item->discount_value,
                    'discount_type' => $item->discount_type,
                ];
                $arrCatalogueId = [];
                foreach($item->productCatalogues as $itemCatalogue) {
                    $arrCatalogueId[] = $itemCatalogue->id;
                    if(count($itemCatalogue->descendants)) {
                        $arrCatalogueId = array_merge($arrCatalogueId, $itemCatalogue->descendants->pluck('id')->toArray());
                    }
                }
                $temp['product_catalogue_id'] = array_unique($arrCatalogueId);
                $data[] = $temp;
            }
            return $data;
        }
    }

    if (!function_exists('setupDataProductByWidget')) {
        function setupDataProductByWidget($data, $promotionCatalogues = []) {
            // dd($promotionCatalogues);
            foreach($data as $key => $item) {
                $maxDiscount = 0;
                $priceAfterTax = 0;
                if(count($item->product_variants) == 0) {
                    //TH sản phẩm có 1 phiên bản
                    $maxDiscount = 0;
                    $priceAfterTax = calculatePriceAfterTax($item->tax_status, $item->output_tax, $item->price); 
                    //TH các chương trình khuyến mãi được áp dụng vào từng sản phẩm
                    if(count($item->promotions)) {
                        foreach($item->promotions as $index => $itemPromotion) {
                            if(compareCurrentTime($itemPromotion->start_date) !== 'later'  && ($itemPromotion->never_end_date == 1 || compareCurrentTime($itemPromotion->end_date) !== 'earlier') && $itemPromotion->publish == __('config')['activePublish']) {
                                $maxDiscount = max($maxDiscount, calculatePriceDiscount($priceAfterTax, $itemPromotion->discount_type, $itemPromotion->discount_value));
                            }
                        }
                    }
                    //TH các chương trình khuyến mãi được áp dụng vào danh mục sản phẩm
                    if(count($promotionCatalogues)) {
                        foreach($item->product_catalogues->pluck('id')->toArray() as $val) {
                            foreach($promotionCatalogues as $promotion) {
                                if(in_array($val, $promotion['product_catalogue_id'])) {
                                    $maxDiscount = max($maxDiscount, calculatePriceDiscount($priceAfterTax, $promotion['discount_type'], $promotion['discount_value']));
                                }
                            }
                        }
                    }
                }else{
                    //TH sản phẩm có nhiều phiên bản
                    $priceAfterTax = calculatePriceAfterTax($item->tax_status, $item->output_tax, $item->product_variants[0]->price);
                    $nameVariant = $item->product_variants[0]->name;
                    foreach($item->product_variants as $itemVariant) {
                        $price = calculatePriceAfterTax($item->tax_status, $item->output_tax, $itemVariant->price);
                        //TH các chương trình khuyến mãi được áp dụng vào từng sản phẩm
                        if(count($item->promotions)) {
                            foreach($item->promotions as $itemPromotion) {
                                if($itemPromotion->pivot->product_variant_id == $itemVariant->id && compareCurrentTime($itemPromotion->start_date) !== 'later'  && ($itemPromotion->never_end_date == 1 || compareCurrentTime($itemPromotion->end_date) !== 'earlier') && $itemPromotion->publish == __('config')['activePublish']) {
                                    $discount = calculatePriceDiscount($price, $itemPromotion->discount_type, $itemPromotion->discount_value);
                                    if($discount > $maxDiscount) {
                                        $maxDiscount = $discount;
                                        $nameVariant = $itemVariant->name;
                                        $priceAfterTax = $price;
                                    }
                                }
                            }
                        }
                        //TH các chương trình khuyến mãi được áp dụng vào danh mục sản phẩm
                        if(count($promotionCatalogues)) {
                            foreach($item->product_catalogues->pluck('id')->toArray() as $val) {
                                foreach($promotionCatalogues as $promotion) {
                                    if(in_array($val, $promotion['product_catalogue_id'])) {
                                        $discount = calculatePriceDiscount($price, $promotion['discount_type'], $promotion['discount_value']);
                                        if($discount > $maxDiscount) {
                                            $maxDiscount = $discount;
                                            $nameVariant = $itemVariant->name;
                                            $priceAfterTax = $price;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $item->name = $item->name . $nameVariant;
                }
                $item->discount = $maxDiscount;
                $item->priceAfterTax = $priceAfterTax;
            }
            return $data;
        }
    }

    if (!function_exists('formatStringToCarbon')) {
        function formatStringToCarbon($string) {
            return Carbon::parse($string);
        }
    }

    if (!function_exists('setupBreadCrumb')) {
        function setupBreadCrumb($data) {
            $html = '<li class="divider">/</li>
                <li class="divider"><a class="home block text-blue" href="'. setupUrl($data->canonical) .'" previewlistener="true">'. $data->name .'</a></li>';
            if(count($data->children)) {
                $html .= setupBreadCrumb($data->children[0]);
            }else{
                return $html;
            }
            return $html;
        }
    }

    if (!function_exists('setupUrl')) {
        function setupUrl($canonical, $isSuffix = true) {
            if($canonical == '' || $canonical == '#' || strpos($canonical, 'http') !== false) {
                return $canonical;
            }else{
                return config('apps.general.APP_URL') . str_replace('/', '', $canonical) . ($isSuffix ? config('apps.general.suffix') : '');
            }
        }
    }
    
    if (!function_exists('caculaterFinalPrice')) {
        function caculaterFinalPrice($product, $price) {
            $finalPrice = $price;
            if(count($product->promotions)) {
                foreach ($product->promotions as $key => $promotion) {
                    if(($promotion->never_end_date == 1 || (compareCurrentTime($promotion->start_date) !== 'later' && compareCurrentTime($promotion->end_date) !== 'earlier')) && $promotion->publish == 2) {
                        $priceDiscount = $price - calculatePriceDiscount($price, $promotion->discount_type, $promotion->discount_value);
                        $finalPrice = min($priceDiscount, $finalPrice);
                    }
                }
            }
            if(count($product->promotion_catalogues)) {
                foreach ($product->promotion_catalogues as $key => $promotion) {
                    $priceDiscount = $price -  calculatePriceDiscount($price, $promotion['discount_type'], $promotion['discount_value']);
                    $finalPrice = min($priceDiscount, $finalPrice);
                }
            }
            return $finalPrice;
        }
    }









