<div class="wrap_rating wrap_border">
    <div class=" bg-coverrate hidden"></div>
    <div class="rating-topzone rc-tgdd">
        <div class="rating-topzonecmt-hascmt">
            <div class="boxrate rate-topzone">
                <h2 class="boxrate__title">{{$product->name}}</h2>
                <div class="boxrate__top">
                    <div class="box-star">
                        {{-- Số sao, số đánh giá --}}
                        <div class="point">
                            <p class="starRatingInfo">{{$ratingSummary['average_rating'] !== null ? round($ratingSummary['average_rating'], 1) : 5}}</p>
                            <div class="rateYo"></div>
                            <span class="total-cmtrt">{{$ratingSummary['total_ratings']}} đánh giá </span>
                        </div>
                        {{-- % đánh giá cho từng sao --}}
                        <ul class="rate-list">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $ratingQuantity = isset($ratingCount[$i]) ? $ratingCount[$i] : 0;
                                    $ratingPercent = $ratingQuantity / ($ratingSummary['total_ratings'] !== 0 ? $ratingSummary['total_ratings'] : 1) * 100;
                                @endphp
                                <li>
                                    <div class="number-star">
                                        {{$i}}<i class="iconcmt-blackstar"></i>
                                    </div>
                                    <div class="timeline-star">
                                        <p class="timing" style="width: {{$ratingPercent}}%"></p>
                                    </div>
                                    <span class="number-percent">{{round($ratingPercent)}}%</span>
                                </li>
                            @endfor
                        </ul>
                    </div>
                    {{-- popup Đánh giá --}}
                    @include('frontend.product.components.popup_comment')
                    <div class="bgcover-success hidden"></div>
                    <div class="popup-success hidden">
                        <h3 class="txt"><b>Cảm ơn bạn đã đánh giá sản phẩm</b></h3>
                        <p class="content">Hệ thống sẽ kiểm duyệt đánh giá của bạn về <b>{{$product->name}}</b> và đăng lên sau 24h nếu phù hợp với quy định đánh giá</p>
                        <div class="close-popup-success">Đóng</div>
                    </div>
                </div>
                <div class="box-filter-comment flex">
                    <div class="mr5">Lọc theo:</div>
                    <span data-filter="latest" class="filter-comment-item active-filter-comment">Mới nhất</span>
                    <span data-filter="album" class="filter-comment-item">Có hình ảnh</span>
                    <span data-filter="star" data-star="5" class="filter-comment-item">5 Sao</span>
                    <span data-filter="star" data-star="4" class="filter-comment-item">4 Sao</span>
                    <span data-filter="star" data-star="3" class="filter-comment-item">3 Sao</span>
                    <span data-filter="star" data-star="2" class="filter-comment-item">2 Sao</span>
                    <span data-filter="star" data-star="1" class="filter-comment-item">1 Sao</span>
                </div>
                <div class="rt-list">
                    <input type="hidden" class="fRLimit" value="{{__('config')['productCommentPerpage']}}">
                    <input type="hidden" class="fRQuantityPerpage" value="{{__('config')['productCommentPerpage']}}">
                    <input type="hidden" class="fRQuantityTotal" value="{{$ratingSummary['total_ratings']}}">
                    {{-- Danh sách đánh giá --}}
                    <ul class="comment-list">
                        @if(count($comments))
                        @foreach($comments as $comment)
                            <li class="par">
                                <div class="cmt-top">
                                    <p class="cmt-top-name">{{$comment->name}}</p>
                                    @if(isset($comment->customers))
                                        <div class="confirm-buy">
                                            <i class="iconcmt-confirm"></i>
                                            Khách hàng tại E+
                                        </div>
                                    @endif
                                </div>
                                <div class="cmt-intro">
                                    <div class="cmt-top-star">
                                        @for($i = 1; $i <= $comment->star_rating; $i++)
                                            <i class="iconcmt-starbuy"></i>
                                        @endfor
                                        @if(5 - $comment->star_rating)
                                            @for($i = 1; $i <= (5 - $comment->star_rating); $i++)
                                                <i class="iconcmt-unstarbuy"></i>
                                            @endfor
                                        @endif
                                    </div>
                                </div>
                                <div class="cmt-content ">
                                    <p class="cmt-txt">{{$comment->content}}</p>
                                </div>
                                @if(isset($comment->album))
                                <div class="album-comment-customer flex">
                                    @foreach($comment->album as $imageItem)
                                    <p class="it-img mt10">
                                        <img class="lazyloaded" src="{{asset($imageItem)}}" alt="img">
                                    </p>
                                    @endforeach
                                </div>
                                @endif
                                <div class="cmt-command">
                                    <a data-id="{{$comment->id}}" class="cmtl dot-circle-ava likeComment"
                                        data-like="{{$comment->likes_count}}">
                                        <i class="far fa-thumbs-up"></i>Hữu ích (<span class="countLiked">{{$comment->likes_count}}</span>)
                                    </a>
                                    <span class="cmtd dot-line">{{formatStringToCarbon($comment->created_at)->diffForHumans()}}</span>
                                </div>
                            </li>
                        @endforeach
                        @endif
                    </ul>
                    <div class="box-flex">
                        @if($ratingSummary['total_ratings'] !== 0 && $ratingSummary['total_ratings'] > __('config')['productCommentPerpage'])
                            <span class="c-btn-rate btn-view-all" >Xem {{$ratingSummary['total_ratings'] - __('config')['productCommentPerpage']}} đánh giá</span>
                        @endif
                        <div class="c-btn-rate btn-write">Viết đánh giá</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>