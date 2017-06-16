@extends('admin::layouts.master')

@section('styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('public/assets/admin/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('public/assets/admin/global/plugins/bootstrap-formhelpers/dist/css/bootstrap-formhelpers.min.css') }}"/>
    <link rel="stylesheet" type="text/css"
          href="{{ asset('public/assets/admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}"/>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject bold uppercase">@if(isset($category))
                                Edit {{$category->name}} @else Add New Category @endif</span>
                    </div>
                </div>
                <!-- BEGIN PAGE BASE CONTENT -->
                <?php
                    $id = e(\Request::get('id'));
                    if (isset($action)) {
                        $get_parameters = ($action == 'add') ? "lang=" . $current_lang : "lang=" . $current_lang . "&id=" . $id;
                    }
                ?>
                <form class="form-horizontal" method="post" enctype="multipart/form-data"
                      action="@if(isset($action) && $action == 'edit'){{ route('admin.category.edit.post', $category->id) }} @else  {{ route('admin.category.add.post', $get_parameters) }} @endif">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="current_lang" value="{{ $current_lang }}">
                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label for="name">Name</label> <span class="required" aria-required="true">*</span>
                                    <input type="text" name="name" id="name" class="form-control" maxlength="255"
                                           placeholder="Name" value="{{ isset($category) && $category->translate($current_lang) !== null ? $category->translate($current_lang)->name : old('name') }}">
                                    The name is how it appears on your site.
                                    @if ($errors->has('name'))
                                        <span class="help-block">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('slug') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label for="slug">Slug</label> <span class="required" aria-required="true">*</span>
                                    <input type="text" name="slug" id="slug" class="form-control" maxlength="255"
                                           placeholder="Slug" value="{{ isset($category) ? $category->slug : old('slug') }}">
                                    The “slug” is the URL-friendly version of the name. It is usually all lowercase and
                                    contains only letters, numbers, and hyphens.
                                    @if ($errors->has('slug'))
                                        <span class="help-block">{{ $errors->first('slug') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label for="parent">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="articles"
                                                @if(isset($category)) @if($category->type=='articles') selected @endif @endif>Articles</option>
                                        <option value="cases"
                                                @if(isset($category)) @if($category->type=='cases') selected @endif @endif>Cases</option>
                                        <option value="downloads"
                                                @if(isset($category)) @if($category->type=='downloads') selected @endif @endif>Downloads</option>

                                    </select>

                                    @if ($errors->has('type'))
                                        <span class="help-block">{{ $errors->first('type') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description"
                                              class="form-control">{{ isset($category) && $category->translate($current_lang) !== null ? $category->translate($current_lang)->description : old('description') }}</textarea>
                                    The description is not prominent by default; however, some themes may show it.
                                    @if ($errors->has('description'))
                                        <span class="help-block">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group {{ $errors->has('locale') ? ' has-error' : '' }} lang">
                                <div class="col-md-12">
                                    <label for="language">Language</label>
                                    <div class="row">
                                        <div class="col-md-1">
                                            @if(isset($language))
                                                @foreach($language as $lang)
                                                    @if($current_lang == $lang->locale)  <i
                                                            class="glyphicon bfh-flag-{{$lang->icon}}"></i> @endif
                                                @endforeach
                                            @endif

                                        </div>
                                        <div class="col-md-10">
                                            <select name="locale" id="language" class="form-control">
                                                <option value="" hidden>Select language...</option>
                                                @if(isset($language))
                                                    @foreach($language as $lang)
                                                        <option value="{{ $lang->locale }}"
                                                                data-flag="{{ $lang->icon }}"
                                                                @if($current_lang == $lang->locale) selected @endif>{{ $lang->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    Sets the language
                                    @if ($errors->has('locale'))
                                        <span class="help-block">{{ $errors->first('locale') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- end of form "Add named button" -->
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="submit" class="btn blue"
                                           value="@if(isset($category)) Edit @else Add New Category @endif">
                                </div>
                            </div>
                        </div>


                        <div class="col-md-8">

                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-comments"></i>Articles
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                           id="articlestable">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            @if(isset($language_icon))
                                                @foreach($language_icon as $i)
                                                    <th><i class="glyphicon bfh-flag-{{ $i }}"></i></th>
                                                @endforeach
                                            @endif
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-comments"></i>Cases
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                           id="casestable">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            @if(isset($language_icon))
                                                @foreach($language_icon as $i)
                                                    <th><i class="glyphicon bfh-flag-{{ $i }}"></i></th>
                                                @endforeach
                                            @endif
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="portlet box green">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-comments"></i>Downloads
                                    </div>
                                    <div class="tools">
                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                        <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                           id="downloadstable">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            @if(isset($language_icon))
                                                @foreach($language_icon as $i)
                                                    <th><i class="glyphicon bfh-flag-{{ $i }}"></i></th>
                                                @endforeach
                                            @endif
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>


                        </div>

                    </div>
                </form>
                <!-- END PAGE BASE CONTENT -->
            </div>
        </div>

        @stop

        @section('scripts')
            <script src="{{ asset('public/assets/admin/global/scripts/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('public/assets/admin/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}"></script>
            <script src="{{ asset('public/assets/admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}"
                    type="text/javascript"></script>
            <script src="{{ asset('public/assets/admin/global/plugins/ckeditor/ckeditor.js') }}"
                    type="text/javascript"></script>

            <script type="text/javascript">
                $(function () {

                    var oTable = $('#articlestable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('admin.category.articles.get') }}",
                        },
                        columns: [

                            {data: 'name', name: 'name'},
                            {data: 'slug', name: 'slug'},

                                @if(isset($language_available))
                                @foreach($language_available as $l)
                            {
                                data: "{{{ $l }}}", name: 'category.id', orderable: false, searchable: false
                            },
                                @endforeach
                                @endif

                            {
                                data: 'action', name: 'action', orderable: false, searchable: false
                            }

                        ]
                    });
                    oTable.draw();
                    $('#search-form').on('submit', function (e) {
                        oTable.draw();
                        e.preventDefault();
                    });
                });


                $(function () {

                    var oTable = $('#casestable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('admin.category.cases.get') }}",
                        },
                        columns: [

                            {data: 'name', name: 'name'},
                            {data: 'slug', name: 'slug'},

                                @if(isset($language_available))
                                @foreach($language_available as $l)
                            {
                                data: "{{{ $l }}}", name: 'category.id', orderable: false, searchable: false
                            },
                                @endforeach
                                @endif

                            {
                                data: 'action', name: 'action', orderable: false, searchable: false
                            }

                        ]
                    });
                    oTable.draw();
                    $('#search-form').on('submit', function (e) {
                        oTable.draw();
                        e.preventDefault();
                    });
                });


                $(function () {

                    var oTable = $('#downloadstable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('admin.category.downloads.get') }}",
                        },
                        columns: [

                            {data: 'name', name: 'name'},
                            {data: 'slug', name: 'slug'},

                                @if(isset($language_available))
                                @foreach($language_available as $l)
                            {
                                data: "{{{ $l }}}", name: 'category.id', orderable: false, searchable: false
                            },
                                @endforeach
                                @endif

                            {
                                data: 'action', name: 'action', orderable: false, searchable: false
                            }

                        ]
                    });
                    oTable.draw();
                    $('#search-form').on('submit', function (e) {
                        oTable.draw();
                        e.preventDefault();
                    });
                });
            </script>
@stop