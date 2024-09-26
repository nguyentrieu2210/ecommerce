<div class="popup-rating-topzone hidden">
    <div class="close-rate"><i class="fas fa-times closeModalComment"></i></div>
    <p class="txt">Đánh giá sản phẩm</p>
    <div class="bproduct">
        <div class="img">
            <img src="{{asset($product->image)}}"
                alt="{{$product->name}}">
        </div>
        <h3>{{$product->name}}</h3>
    </div>
    <ul class="rating-topzonecr-star">
        <li class="choose-star" data-val="1">
            <i class="iconcmt-unstarlist active"></i>
            <p data-val="1">Rất tệ</p>
        </li>
        <li class="choose-star" data-val="2">
            <i class="iconcmt-unstarlist active"></i>
            <p data-val="2">Tệ</p>
        </li>
        <li class="choose-star" data-val="3">
            <i class="iconcmt-unstarlist active"></i>
            <p data-val="3">Tạm ổn</p>
        </li>
        <li class="choose-star" data-val="4">
            <i class="iconcmt-unstarlist active"></i>
            <p data-val="4" class="active-slt">Tốt</p>
        </li>
        <li class="choose-star" data-val="5">
            <i class="iconcmt-unstarlist active"></i>
            <p data-val="5">Rất tốt</p>
        </li>
    </ul>
    <form action="" method="POST" class="form-rate">
        <div class="boxFRContent">
            <textarea class="input-data fRContent" name="content" placeholder="Mời bạn chia sẻ thêm cảm nhận..."></textarea>
        </div>
        <div class="form-column">
            <div class="upload__box  ">
                <div class="upload__btn-box">
                    <label class="upload__btn">
                        <span class="send-img">
                            <i class="iconcmt-camera"><input style="display:none" id="commentAlbum" type="file" name="files[]" accept="image/*" multiple></i>
                            <p>Gửi ảnh thực tế <span>(tối đa 3 ảnh)</span></p>
                        </span>
                    </label>
                </div>
                <div class="upload__img-wrap">
                    {{-- <div class="image-comment-item">
                        <img src="/backend/img/empty-image.png" alt="">
                        <i class="fas fa-times-circle"></i>
                    </div> --}}
                </div>
            </div>
        </div>
        @php
            $name = Auth::guard('customer')->user()->name ?? '';
            $phone = Auth::guard('customer')->user()->phone ?? '';
            $idCustomer = Auth::guard('customer')->user()->id ?? '';
        @endphp
        <div class="item">
            <div class="w50">
                <input value="{{$name}}" type="text" class="input-data fRName" name="name" placeholder="Họ tên (bắt buộc)">
            </div>
            <div class="w50">
                <input value="{{$phone}}" type="text" class="input-data fRPhone" name="phone" placeholder="Số điện thoại (bắt buộc)">
            </div>
            <input type="hidden" name="model_id" value="{{$product->id}}">
            <input type="hidden" name="model" value="product">
            <input type="hidden" name="star_rating" value="5">
            <input type="hidden" name="customer_id" value="{{$idCustomer}}">
        </div>
    </form>
    <div class="dcap">
        <button type="submit" id="submitrt" class="submit send-rate ">Gửi đánh giá</button>
    </div>
</div>