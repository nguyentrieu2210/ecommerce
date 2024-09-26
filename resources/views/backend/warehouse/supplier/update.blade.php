@extends('backend.layouts.layout')
@section('title', 'E-commerce | '.$supplier->name)
@section('content')
    @include('backend.layouts.components.breadcrump', ['config' => $config])

    <div class="wrapper wrapper-content animated fadeInRight">
        @php
            $route = $config['method'] == 'create' ? route('supplier.store') : route('supplier.update', $supplier->id);   
        @endphp
        <div class="row">
            <div class="flex col-lg-12 box-title-supplier">
                <span class="btn btn-back"><a href="{{route('supplier.index')}}"><i class="fa fa-arrow-left"></i></a></span>
                <h5 class="name-supplier">{{$supplier->name}}</h5>
                @if($supplier->publish == 2)
                <span class="status-supplier-active">Đang hoạt động</span>
                @else
                <span class="status-supplier-stop">Dừng hoạt động</span>
                @endif
            </div>
            <div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5 class="code-supplier">Mã nhà cung cấp:{{$supplier->code}}</h5>
                        <div class="ibox-tools">
                            <span class="dropdown-toggle filter-history" data-toggle="dropdown">
                                {{-- <span>Tháng này (01/07/2024 - 31/07/2024) </span> <i class="fa fa-caret-down"></i> --}}
                            </span>
                            <ul class="dropdown-menu dropdown-customer">
                                @foreach (__('filter.createdAt') as $key => $val)
                                    <li class="filter-history-item"><a class="dropdown-item" href="{{$key}}">{{$val}}</a><br></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="ibox-content info-figure" style="">
                        <div class="row">
                            <div class="col-sm-6">
                                <span>Đơn nhập</span><br>
                                <span>đã tạo</span><br>
                                <span class="text-primary">{{$receiveInventoryInfo['totalQuantity']}} đơn</span><br>
                                <span class="fw510 fs17 text-black-theme"><span class="style-money">{{formatNumberFromSql($receiveInventoryInfo['totalPrice'])}}</span class="style-currency">đ<span></span></span>
                            </div>
                            <div class="col-sm-6">
                                <span>Đơn đã nhập</span><br>
                                <span>chưa thanh toán</span><br>
                                <span class="text-primary">{{$receiveInventoryInfo['totalQuantityUnpaid']}} đơn</span><br>
                                <span class="fw510 fs17 text-black-theme"><span class="style-money">{{formatNumberFromSql($receiveInventoryInfo['totalPriceUnpaid'])}}</span class="style-currency">đ<span></span></span>
                            </div>
                            {{-- <div class="col-sm-3">
                                <span>Đơn trả</span><br>
                                <span>đã tạo</span><br>
                                <span class="text-danger">2 đơn</span><br>
                                <span><span class="style-money">3,991,975</span class="style-currency">đ<span></span></span>
                            </div>
                            <div class="col-sm-3">
                                <span>Đơn trả</span><br>
                                <span>chưa nhận hoàn tiền</span><br>
                                <span class="text-danger">0 đơn</span><br>
                                <span><span class="style-money">0</span class="style-currency">đ<span></span></span>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5 class="code-supplier">Lịch sử nhập/trả hàng</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content info-figure" style="">
                        <div class="list-history">
                            {{-- <div class="item-history flex-space-between">
                                <div class="item-history-l flex">
                                    <svg width='32' height='32' viewBox='0 0 32 32' fill='none' xmlns='http://www.w3.org/2000/svg'><circle cx='16' cy='16' r='16' fill='#FFEEEE'/><path d='M16.6167 14.6933C16.4814 14.5977 16.3895 14.4523 16.3614 14.289C16.3333 14.1257 16.3711 13.9579 16.4667 13.8225L17.4884 12.3725C17.5051 12.3545 17.5229 12.3375 17.5417 12.3217C17.5776 12.2775 17.6197 12.2388 17.6667 12.2067C17.7719 12.1391 17.8951 12.1048 18.0201 12.1082C18.1451 12.1116 18.2661 12.1526 18.3675 12.2258L19.8034 13.2375C19.9292 13.3366 20.0122 13.4803 20.0353 13.6388C20.0584 13.7973 20.0199 13.9587 19.9276 14.0896C19.8353 14.2205 19.6963 14.3111 19.5393 14.3427C19.3822 14.3742 19.219 14.3443 19.0834 14.2592L18.8825 14.1167L19.1984 15.9067C19.2272 16.0699 19.19 16.2379 19.095 16.3737C19 16.5095 18.8549 16.602 18.6917 16.6308C18.6557 16.6374 18.6191 16.6408 18.5825 16.6408C18.4356 16.6407 18.2935 16.5889 18.181 16.4944C18.0686 16.3999 17.993 16.2688 17.9675 16.1242L17.6492 14.32L17.4925 14.5425C17.3965 14.6775 17.251 14.7691 17.0877 14.7973C16.9245 14.8256 16.7567 14.7882 16.6209 14.6933H16.6167ZM13.3617 13.6817C13.2556 13.2634 13.2353 12.8279 13.302 12.4015C13.3687 11.9751 13.5211 11.5667 13.75 11.2008C14.0159 10.8606 14.348 10.5779 14.7263 10.3697C15.1046 10.1616 15.5212 10.0324 15.9509 9.99L18.9784 9.455C21.03 9.09333 22.3117 9.9875 22.67 12.0383L23.2042 15.0667C23.3108 15.4848 23.3315 15.9202 23.2652 16.3466C23.1989 16.773 23.0469 17.1815 22.8184 17.5475C22.5526 17.8877 22.2207 18.1704 21.8425 18.3785C21.4644 18.5866 21.0479 18.7159 20.6184 18.7583L17.59 19.2925C17.3121 19.3438 17.0301 19.3703 16.7475 19.3717C15.1867 19.3717 14.2084 18.4633 13.8992 16.705L13.3617 13.6817ZM14.5925 13.465L15.1259 16.4933C15.3684 17.865 15.9959 18.3058 17.3709 18.065L20.3984 17.53C20.9363 17.4907 21.4368 17.2402 21.7909 16.8333C22.0524 16.3616 22.1168 15.8057 21.97 15.2867L21.4359 12.2583C21.235 11.1217 20.7692 10.6233 19.8359 10.6233C19.6197 10.6257 19.4042 10.6469 19.1917 10.6867L16.1667 11.2208C15.6286 11.2604 15.1281 11.5111 14.7742 11.9183C14.5118 12.3897 14.4465 12.9457 14.5925 13.465ZM24.115 19.875C24.1294 19.9558 24.1277 20.0387 24.11 20.1189C24.0923 20.199 24.059 20.2749 24.0119 20.3422C23.9649 20.4095 23.905 20.4668 23.8357 20.5109C23.7665 20.555 23.6892 20.585 23.6084 20.5992L14.9284 22.1267C14.8769 22.5354 14.7159 22.9226 14.4624 23.2474C14.209 23.5721 13.8724 23.8224 13.4885 23.9716C13.1045 24.1208 12.6873 24.1635 12.281 24.0951C11.8748 24.0268 11.4946 23.8499 11.1806 23.5832C10.8666 23.3165 10.6306 22.9699 10.4974 22.58C10.3642 22.1902 10.3388 21.7716 10.424 21.3685C10.5091 20.9655 10.7016 20.5929 10.9811 20.2902C11.2606 19.9876 11.6167 19.7661 12.0117 19.6492L10.31 9.98333C10.2603 9.7115 10.1051 9.47037 9.87824 9.31253C9.65141 9.1547 9.37136 9.09298 9.09919 9.14083L8.60419 9.22417C8.44115 9.25087 8.27418 9.21193 8.13977 9.11587C8.00536 9.01981 7.91445 8.87445 7.88692 8.71155C7.85938 8.54866 7.89746 8.38149 7.99283 8.24659C8.0882 8.11169 8.2331 8.02004 8.39585 7.99167L8.88752 7.90833C9.48527 7.8064 10.0991 7.94438 10.5957 8.29232C11.0924 8.64025 11.4317 9.17005 11.54 9.76667L13.2742 19.6333C13.5968 19.7223 13.8961 19.8808 14.1509 20.0977C14.4057 20.3147 14.6099 20.5848 14.7492 20.8892L23.3917 19.3683C23.4725 19.3538 23.5553 19.3555 23.6355 19.3731C23.7157 19.3908 23.7915 19.4241 23.8588 19.4712C23.926 19.5183 23.9832 19.5782 24.0272 19.6475C24.0712 19.7168 24.101 19.7941 24.115 19.875ZM13.7084 21.8333C13.7084 21.6273 13.6473 21.4259 13.5328 21.2546C13.4183 21.0833 13.2557 20.9498 13.0653 20.871C12.875 20.7921 12.6655 20.7715 12.4635 20.8117C12.2614 20.8519 12.0758 20.9511 11.9301 21.0968C11.7844 21.2424 11.6852 21.4281 11.645 21.6301C11.6048 21.8322 11.6255 22.0416 11.7043 22.232C11.7832 22.4223 11.9167 22.585 12.088 22.6994C12.2593 22.8139 12.4607 22.875 12.6667 22.875C12.9428 22.8746 13.2075 22.7647 13.4028 22.5694C13.598 22.3742 13.7079 22.1095 13.7084 21.8333Z' fill='#EE4747'/></svg>
                                    <div class="item-history-l-r">
                                        <span>
                                            Đơn trả <a class="text-primary" href="">SRT00004</a>
                                        </span><br>
                                        <span>12/07/2024 17:45</span>
                                    </div>
                                </div>
                                <div class="item-history-r text-right">
                                    <span>2,328,000₫</span><br>
                                    <span class="status-complete">Đã hoàn trả</span>
                                    <span class="status-incomplete">Chưa nhận hoàn tiền</span>
                                </div>
                            </div> --}}
                            @if(count($receiveInventories))
                                @foreach($receiveInventories as $item)
                                    <div class="item-history flex-space-between">
                                        <div class="item-history-l flex">
                                            <svg width='32' height='32' viewBox='0 0 32 32' fill='none' xmlns='http://www.w3.org/2000/svg'><circle cx='16' cy='16' r='16' fill='#E6F4FF'/><path d='M13.3634 13.6836C13.1485 12.8398 13.2891 11.9414 13.7501 11.2031C14.2891 10.5156 15.0821 10.0781 15.9493 9.99219L18.9766 9.45703C21.0274 9.09375 22.3087 9.98828 22.6681 12.0391L23.2032 15.0664C23.4181 15.9102 23.2774 16.8086 22.8165 17.5469C22.2813 18.2344 21.4845 18.6719 20.6173 18.7578L17.5899 19.293C17.3126 19.3437 17.0313 19.3711 16.7462 19.3711C15.1837 19.3711 14.2071 18.4609 13.8985 16.7031L13.3634 13.6836ZM14.5938 13.4648L15.129 16.4922C15.3712 17.8633 16.0001 18.3047 17.3751 18.0625L20.4024 17.5273C20.9415 17.4883 21.4415 17.2383 21.7931 16.832C22.0548 16.3594 22.1173 15.8047 21.9727 15.2852L21.4376 12.2578C21.2384 11.1211 20.7696 10.6211 19.836 10.6211C19.6212 10.625 19.4024 10.6445 19.1915 10.6836L16.1681 11.2187C15.629 11.2578 15.129 11.5078 14.7774 11.918C14.5118 12.3906 14.4454 12.9453 14.5938 13.4648ZM24.1134 19.875C24.172 20.2148 23.9493 20.5391 23.6095 20.5977L14.9298 22.125C14.7735 23.3789 13.629 24.2656 12.3751 24.1055C11.1212 23.9453 10.2345 22.8047 10.3946 21.5508C10.5079 20.6484 11.1446 19.9023 12.0157 19.6445L10.3087 9.98437C10.2032 9.41797 9.66415 9.04297 9.09774 9.14062L8.60165 9.22266C8.2618 9.27734 7.94149 9.04687 7.8829 8.70703C7.82821 8.36719 8.05477 8.05078 8.39071 7.99219L8.8829 7.91016C10.1251 7.69922 11.3087 8.52734 11.5352 9.76953L13.2735 19.6328C13.9259 19.8125 14.4649 20.2734 14.7501 20.8867L23.3907 19.3672C23.7306 19.3086 24.0548 19.5312 24.1134 19.875ZM13.7071 21.832C13.7071 21.2578 13.2423 20.7891 12.6641 20.7891C12.086 20.7891 11.6251 21.2578 11.6251 21.832C11.6251 22.4062 12.0899 22.875 12.6681 22.875C13.2423 22.875 13.7071 22.4102 13.7071 21.832Z' fill='#007CE8'/><path d='M18.0079 12.1094C18.3126 12.1094 18.5704 12.3281 18.6212 12.625L18.9376 14.4297L19.0939 14.207C19.2931 13.9258 19.6837 13.8594 19.9649 14.0547H19.9689C20.2501 14.2539 20.3165 14.6445 20.1173 14.9258L19.0939 16.375C19.0782 16.3945 19.0587 16.4102 19.0392 16.4258C19.004 16.4687 18.961 16.5078 18.9142 16.5391C18.6993 16.6758 18.422 16.668 18.2149 16.5195L16.7774 15.5078C16.504 15.293 16.461 14.9023 16.672 14.6289C16.8712 14.3789 17.2267 14.3164 17.4962 14.4844L17.6954 14.625L17.379 12.8359C17.3204 12.4961 17.547 12.1719 17.8868 12.1133C17.9376 12.1133 17.9728 12.1094 18.0079 12.1094Z' fill='#007CE8'/></svg>
                                            <div class="item-history-l-r">
                                                <span>
                                                    Đơn nhập <a class="text-primary" href="/admin/receiveInventory/{{$item->id}}/edit">{{$item->code}}</a>
                                                </span><br>
                                                <span>{{formatDateTimeFromSql($item->created_at)}}</span>
                                            </div>
                                        </div>
                                        <div class="item-history-r text-right">
                                            <span>{{formatNumberFromSql(calculateFinalTotalCost($item))}}<span class="currency">đ</span></span><br>
                                            <span class="{{__('config.statusReceive')[$item->status_receive_inventory]}}">{{__('filter.statusReceive')[$item->status_receive_inventory]}}</span>
                                            <span class="{{__('config.statusPayment')[$item->status_payment]}}">{{__('filter.statusPayment')[$item->status_payment]}}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        {{-- <div class="pagination">
                            <i class="fa fa-chevron-left"></i>
                            <span class="pagination-list">
                                <span class="pagination-item">1</span>
                            </span>
                            <i class="fa fa-chevron-right"></i>
                        </div> --}}
                        {{$receiveInventories->appends(request()->query())->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="editSupplier" class="modal fade" aria-hidden="true">
                    <input type="hidden" class="inputCanonical" value="" >
                    <input type="hidden" class="targetId" value="">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header flex modal-header-customize">
                                <h5 class="modal-title fs20">Cập nhật thông tin nhà cung cấp</h5>
                            </div>
                            <form method="post" action="{{route('supplier.update', $supplier->id)}}">
                                @csrf
                                <input type="hidden" data-target="modalEdit" class="errorModal" value="{{$errors->any()}}">
                                <div class="modal-body">
                                    @if ($errors->any())
                                    <div class="alert-error" style="margin:0; padding: 0; width:100%">
                                        <div class="alert-error-box flex">
                                            <i class="fa fa-exclamation-circle"></i>
                                            <div class="list-error">
                                                @foreach ($errors->all() as $error)
                                                    <span>- {{ $error }}</span><br>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group"><label>Tên nhà cung cấp <span class="text-danger">(*)</span></label>
                                                <input type="text" placeholder="Nhập tên nhà cung cấp" value="{{old('name') ?? ($supplier->name ?? '') }}" name="name" class="form-control">
                                            </div>
                                            <div class="form-group"><label>Số điện thoại</label>
                                                <input type="text" placeholder="Nhập số điện thoại" value="{{old('phone') ?? ($supplier->phone ?? '') }}" name="phone" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Quận / Huyện</label>
                                                <select style="width: 100%" name="district_id" class="District ml setupSelect2 changeLocation" data-target="Ward">
                                                    <option value="0">Chọn quận / huyện</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group"><label>Mã nhà cung cấp <span class="text-danger">(*)</span></label>
                                                <input type="text" placeholder="Nhập mã nhà cung cấp" value="{{old('code') ?? ($supplier->code ?? '') }}" name="code" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Tỉnh / Thành phố</label>
                                                <select style="width: 100%" name="province_id" class="Province ml setupSelect2 changeLocation" data-target="District">
                                                    <option value="0">Chọn tỉnh / thành phố</option>
                                                    <div>
                                                        @foreach ($provinces as $key => $val)
                                                        <option {{(old('province_id') ?? ($supplier->province_id !== null ? $supplier->province_id : 0)) == $val->code ? 'selected' : '' }}
                                                            value="{{ $val->code }}">{{ $val->name }}</option>
                                                        @endforeach
                                                    </div>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Phường / Xã</label>
                                                <select style="width: 100%" name="ward_id" class="Ward ml setupSelect2">
                                                    <option value="0">Chọn phường / xã</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group"><label>Địa chỉ cụ thể</label> <input type="text"
                                                placeholder="Nhập địa chỉ cụ thể" name="address" value="{{old('address') ?? ($supplier->address ?? '')}}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group"><label>Email</label>
                                                <input type="text" placeholder="Nhập email" value="{{old('email') ?? ($supplier->email ?? '')}}" name="email" class="form-control">
                                            </div>
                                            <div class="form-group"><label>Website</label>
                                                <input type="text" placeholder="https://" value="{{old('website') ?? ($supplier->website ?? '')}}" name="website" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Trạng thái nhà cung cấp</label>
                                                <select style="width: 100%" name="publish" class="ml setupSelect2">
                                                    @foreach(__('filter.publishSupplier') as $key => $val)
                                                        <option {{(old('publish') ?? $supplier->publish) == $key ? 'selected' : ''}} value="{{$key}}">{{$val}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group"><label>Mã số thuế</label>
                                                <input type="text" placeholder="Nhập mã số thuế" value="{{old('tax_number') ?? ($supplier->tax_number ?? '') }}" name="tax_number" class="form-control">
                                            </div>
                                            <div class="form-group"><label>Số fax</label>
                                                <input type="text" placeholder="Nhập số fax" value="{{old('fax') ?? ($supplier->fax ?? '')}}" name="fax" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-success">Lưu</button>
                                </div>
                            </form>
                        </div>
                        <input type="hidden" class="oldDistrictId" value="{{old('district_id') ?? (isset($supplier) && $supplier->district_id !== null ? $supplier->district_id : '')}}">
                        <input type="hidden" class="oldWardId" value="{{old('ward_id') ?? (isset($supplier) && $supplier->ward_id !== null ? $supplier->ward_id : '')}}">
                    </div>
                </div>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5 class="code-supplier">Liên hệ</h5>
                        <div class="ibox-tools modalEdit" data-toggle="modal" href="#editSupplier">
                            <i class="fa fa-edit edit-info-supplier fs17"></i>
                        </div>
                    </div>
                    <div class="ibox-content info-figure" style="">
                        <div class="item-info mb5">
                            @if($supplier->phone !== null)
                                <span class="title-info">Số điện thoại</span><br>
                                <span class="content-info">{{$supplier->phone}}</span>
                            @else
                                <span class="title-info">Không có số điện thoại</span>
                            @endif
                        </div>
                        <div class="item-info mb5">
                            @if($supplier->email !== null)
                                <span class="title-info">Email</span><br>
                                <span class="content-info">{{$supplier->email}}</span>
                            @else
                                <span class="title-info">Không có email</span>
                            @endif
                        </div>
                        <div class="item-info mb5">
                            @if($supplier->address !== null)
                                <span class="title-info">Địa chỉ</span><br>
                                <span class="content-info">{{$supplier->address}}</span>
                            @else
                                <span class="title-info">Không có địa chỉ</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5 class="code-supplier">Nhân viên phụ trách</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content" style="">
                        <select style="width: 100%" data-id="{{$supplier->id}}" name="user_id" class="ml setupSelect2">
                            <div>
                                @foreach ($users as $key => $val)
                                <option value="0">Chọn nhân viên phụ trách</option>
                                <option {{$supplier->user_id == $val->id ? 'selected' : '' }}
                                    value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </div>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-button">
            <a href="{{route('supplier.index')}}" class="btn btn-white" >Quay lại</a>
        </div>
    </div>
@endsection
