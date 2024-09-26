@extends('backend.layouts.layout')
@section('title', __("breadcrump.".$config['module'].".".$config['method'].".title"))
@section('content')
 
    @include('backend.layouts.components.breadcrump', ['config' => $config])
    <form method="post" action="{{route('userCatalogue.permission.update')}}">
        @csrf
        <div class="row">
            <div class="col-lg-12 mt20">
                <div class="ibox-title">
                    <h5 class="fs17">Phân quyền</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Danh sách quyền</th>
                            @foreach($userCatalogues as $key => $val)
                                <th class="text-center">{{$val->name}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $key => $val)
                        <tr class="text-center">
                            <td>
                                <div class="flex-space-between">
                                    <span class="text-success">{{$val->name}}</span>
                                    <span class="text-danger">{{$val->canonical}}</span>
                                </div>
                            </td>
                            @foreach($userCatalogues as $keyItem => $valItem)
                                <td>
                                    <input type="checkbox" class="check-permission" {{collect($valItem->permissions)->contains('id', $val->id) ? 'checked' : ''}} name="permission[{{$valItem->id}}][]" value="{{$val->id}}">
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="ibox-button mt20 mb55">
                    <button class="btn btn-primary mr10" type="submit">Cập nhật</button>
                    <a href="{{route('userCatalogue.index')}}" class="btn btn-white" >Quay lại</a>
                </div>
            </div>
        </div>
    </form>
    <input type="hidden" class="name-model" value="{{$config['module']}}">
@endsection
