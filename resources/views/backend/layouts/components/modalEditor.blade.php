<div class="float-e-margins hidden">
    <div class="ibox-content">
        <a data-toggle="modal" class="btn btn-primary clickModal"
            href="#modal-images-editor">modal</a>
    </div>
</div>
{{-- MODAL SETUP IMAGES FOR EDITOR --}}
<div id="modal-images-editor" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="sortable listImage row flex-wrap">
                    {{-- Danh sách hình ảnh cấu hình --}}
                </div>
                <div class="row lead-info">
                    <p>- Nếu bạn không cấu hình kích thước cho hình ảnh. Hệ thống sẽ sử dụng kích thước mặc định của hình ảnh</p>
                    <p>- Để cấu hình chi tiết, bạn chọn hình ảnh, điền thông tin. Sau khi đã điền hết các thông tin cần thiết cho mỗi ảnh, nhấn <strong>hoàn thành</strong> để chuyển các hình ảnh vào phần soạn thảo</p>
                </div>
                <div class="configuration row hidden mt20">
                    <div class="col-sm-6 b-r pt15">
                        <h4 class="m-t-none mb5">CẤU HÌNH</h4>
                        {{-- <small class="text-info-detail">- Nếu bạn chỉ đặt chiều ngang hoặc chiều cao thì kích thước chiều còn lại
                            sẽ được tính toán theo tỉ lệ của hình ảnh</small>
                            <br>
                        <small style="display:block" class="text-info-detail mb10">- Nếu bạn điền đường dẫn đính kèm thì ảnh sẽ có dạng như 1 liên kết, người dùng có thể nhấn vào để chuyển hướng</small> --}}
                        <div class="form-group"><label>Tiêu đề</label> <input value=""
                                name="title" placeholder="Nhập tiêu đề..."
                                class="form-control configurationEditor"></div>
                        <div class="form-group"><label>Chiều ngang</label> <input type="number"
                                value="" min="1" name="width" placeholder="Nhập chiều ngang..."
                                class="form-control configurationEditor onChange"></div>
                        <div class="form-group"><label>Chiều cao</label> <input type="number"
                                value="" min="1" name="height" placeholder="Nhập chiều cao..."
                                class="form-control configurationEditor onChange"></div>
                        <div class="form-group"><label>Đường dẫn đính kèm</label> <input
                                value="" name="link" placeholder="Nhập đường dẫn..."
                                class="form-control configurationEditor"></div>
                        <div>
                            
                        </div>
                    </div>
                    <div class="iboxImageDemo col-sm-6 pt15">
                        <h4 class="m-t-none mb5">HÌNH ẢNH DEMO</h4>
                        {{-- <small class="text-info-detail">Kích thước của hình ảnh DEMO có thể bị thu nhỏ nhưng vẫn giữ nguyên tỉ
                            lệ</small> --}}
                        <p class="imageDemoEmpty text-center">
                            <a href=""><i class="fa fa-sign-in big-icon"></i></a>
                        </p>
                        <div class="imageDemo mt15">

                        </div>
                    </div>
                </div>
                <div class="row ibox-footer">
                    <button class="completeSetupImageForEditor btn btn-primary"
                        type="button">Hoàn thành</button>
                    <button class="cancelSetupImageForEditor btn btn-danger" type="button">Quay lại</button>
                </div>
            </div>
        </div>
    </div>
</div>