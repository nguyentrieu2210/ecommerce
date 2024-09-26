       <div class="ibox float-e-margins">
           <div class="ibox-title">
               <h5 class="ibox-title-customize">LỊCH SỬ ĐƠN ĐẶT HÀNG NHẬP</h5>
               <div class="ibox-tools">
                   <a class="collapse-link">
                       <i class="fa fa-chevron-up"></i>
                   </a>
               </div>
           </div>
           <div class="ibox-content fw510" style="position: relative;">
               <div id="vertical-timeline" class="vertical-container dark-timeline center-orientation">
                    @foreach($histories as $history)
                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon navy-bg">
                                <i class="fa fa-circle"></i>
                            </div>
                            <div class="vertical-timeline-content">
                                {!! $history->content !!}
                                <span class="vertical-date">
                                    {{$history->user_name}} <br>
                                    <small>{{formatDateTimeFromSql($history->created_at)}}</small>
                                </span>
                            </div>
                        </div>
                    @endforeach
               </div>
           </div>
       </div>
