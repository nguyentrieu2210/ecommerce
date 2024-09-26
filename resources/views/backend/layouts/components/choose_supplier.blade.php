<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5 class="ibox-title-customize">NHÀ CUNG CẤP</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content" style="position: relative;">
        <div class="box-search-supplier ">
            <div class="form-group search-supplier {{isset($model) ? 'hidden' : ''}}">
                <i class="fa fa-search"></i>
                <input type="text" class="inputSearchSupplier form-control" value="" placeholder="Tìm kiếm theo tên, SĐT, mã NCC...(F4)">
            </div>
            <div class="render-list-supplier hidden">
            </div>
        </div>
        <input type="hidden" name="supplier_id" value="{{isset($model) ? $model->suppliers->id : ''}}">
        <div class="info-supplier-detail {{isset($model) ? '' : 'hidden'}}">
            <div class="flex-space-between" style="align-items: center">
                <div class="info-supplier flex">
                    <img src="/backend/img/empty-avatar-supplier.png" alt="">
                    <span class="link"><a href="{{isset($model) ? ('/admin/supplier/' . $model->suppliers->id . '/edit') : '#'}}" class="text-primary confirmLink">{{isset($model) ? $model->suppliers->name : '...'}}</a></span><br>
                </div>
                @if($statusDisabled !== 'disabled')
                    <i class="fa fa-times deleteSupplier"></i>
                @endif
            </div>
            
            <p class="text-customize mt10">Thông tin nhà cung cấp</p>
            <div class="info-supplier-b"> 
                @if(isset($model)) 
                    @php
                        $email = $model->suppliers->email;
                        $phone = $model->suppliers->phone;
                        $address = $model->suppliers->address;
                    @endphp
                    @if($email !== null)
                        <span class="text-primary text-normal block mt5">{{$email}}</span>
                    @else
                        <span class="text-none block mt5">Không có email</span>
                    @endif
                    @if($phone !== null)
                        <span class="text-normal block mt5">+{{$phone}}</span>
                    @else
                        <span class="text-none block mt5">Không có số điện thoại</span>
                    @endif
                    @if($address !== null)
                        <span class="text-normal block mt5">{{$address}}</span>
                    @else
                        <span class="text-none block mt5">Không có địa chỉ</span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>