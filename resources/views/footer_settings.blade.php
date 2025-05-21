@extends('layout')

@section('content')

    <h4 class="page-section-heading">Customize Footer Links</h4>
    <div class="">
        <div class="">
            <!-- CSRF -->
            @csrf

            <!-- ALERT SECTION -->
            <div class="row" style="text-align:center;">
                <!-- SHOW VALIDATION ERRORS IF ANY -->
                @if(count($errors))
                <div class="form-group">
                    <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                    </div>
                </div>
                @endif

                @if (Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
            </div>

            <!-- Tabbable Widget -->
            <div class="tabbable tabs-vertical tabs-left">

                <!-- Tabs -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#footer_1" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 1</a></li>
                    <li><a href="#footer_2" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 2</a></li>
                    <li><a href="#footer_3" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 3</a></li>
                    <li><a href="#footer_4" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 4</a></li>
                    <li><a href="#footer_5" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 5</a></li>
                    <li><a href="#footer_6" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 6</a></li>
                    <li><a href="#footer_7" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 7</a></li>
                    <li><a href="#footer_8" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 8</a></li>
                    <li><a href="#footer_9" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 9</a></li>
                    <li><a href="#footer_10" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 10</a></li>
                    <li><a href="#footer_11" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 11</a></li>
                    <li><a href="#footer_12" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 12</a></li>
                    <li><a href="#footer_13" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 13</a></li>
                    <li><a href="#footer_14" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Footer # 14</a></li>
                </ul>
                <!-- // END Tabs -->

                <!-- Panes -->
                <div class="tab-content" style="margin-bottom: 0px;">
                    <!-- FOOTER 1 START -->
                    <div id="footer_1" class="tab-pane active">
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 1</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Type in label .." class="form-control" name="footer_1_label" id="footer_1_label" value="{{ $footerSettings['footer_link_1']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL </label>
                            <input type="text" class="form-control" placeholder="Type # if not needed .." name="footer_1_value" id="footer_1_value" value="{{ $footerSettings['footer_link_1']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_1_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                    <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_1']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(1)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 2 START -->
                    <div id="footer_2" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 2</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_2_label" id="footer_2_label" value="{{ $footerSettings['footer_link_2']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_2_value" id="footer_2_value" value="{{ $footerSettings['footer_link_2']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_2_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_2']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(2)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 3 START -->
                    <div id="footer_3" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 3</h3>
                    
                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_3_label" id="footer_3_label" value="{{ $footerSettings['footer_link_3']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_3_value" id="footer_3_value" value="{{ $footerSettings['footer_link_3']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_3_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                    <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_3']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(3)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 4 START -->
                    <div id="footer_4" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 4</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_4_label" id="footer_4_label" value="{{ $footerSettings['footer_link_4']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_4_value" id="footer_4_value" value="{{ $footerSettings['footer_link_4']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_4_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                    <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_4']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(4)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 5 START -->
                    <div id="footer_5" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 5</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_5_label" id="footer_5_label" value="{{ $footerSettings['footer_link_5']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_5_value" id="footer_5_value" value="{{ $footerSettings['footer_link_5']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_5_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                    <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_5']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(5)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 6 START -->
                    <div id="footer_6" class="tab-pane">
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 6</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_6_label" id="footer_6_label" value="{{ $footerSettings['footer_link_6']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_6_value" id="footer_6_value" value="{{ $footerSettings['footer_link_6']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_6_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                    <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_6']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(6)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 7 START -->
                    <div id="footer_7" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 7</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_7_label" id="footer_7_label" value="{{ $footerSettings['footer_link_7']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_7_value" id="footer_7_value" value="{{ $footerSettings['footer_link_7']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_7_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_7']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(7)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 8 START -->
                    <div id="footer_8" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 8</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_8_label" id="footer_8_label" value="{{ $footerSettings['footer_link_8']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_8_value" id="footer_8_value" value="{{ $footerSettings['footer_link_8']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_8_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_8']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(8)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 9 START -->
                    <div id="footer_9" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 9</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_9_label" id="footer_9_label" value="{{ $footerSettings['footer_link_9']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_9_value" id="footer_9_value" value="{{ $footerSettings['footer_link_9']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_9_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_9']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(9)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 10 START -->
                    <div id="footer_10" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 10</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_10_label" id="footer_10_label" value="{{ $footerSettings['footer_link_10']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_10_value" id="footer_10_value" value="{{ $footerSettings['footer_link_10']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_10_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_10']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(10)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 11 START -->
                    <div id="footer_11" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 11</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_11_label" id="footer_11_label" value="{{ $footerSettings['footer_link_11']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_11_value" id="footer_11_value" value="{{ $footerSettings['footer_link_11']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_11_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                    <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_11']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(11)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 12 START -->
                    <div id="footer_12" class="tab-pane">
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 12</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_12_label" id="footer_12_label" value="{{ $footerSettings['footer_link_12']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_12_value" id="footer_12_value" value="{{ $footerSettings['footer_link_12']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_12_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                    <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_12']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(12)">Save</button>
                        </div>
                    </div>

                    <!-- FOOTER 13 START -->
                    <div id="footer_13" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 13</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_13_label" id="footer_13_label" value="{{ $footerSettings['footer_link_13']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_13_value" id="footer_13_value" value="{{ $footerSettings['footer_link_13']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_13_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                    <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_13']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(13)">Save</button>
                        </div>
                    </div>  

                    <!-- FOOTER 14 START -->
                    <div id="footer_14" class="tab-pane"> 
                        <h3 class="text-h1 ribbon-heading ribbon-primary bottom-left-right">Footer # 14</h3>

                        <!-- LABEL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Label</label>
                            <input type="text" placeholder="Label" class="form-control" name="footer_14_label" id="footer_14_label" value="{{ $footerSettings['footer_link_14']['label'] }}">
                        </div>

                        <!-- URL -->
                        <div class="form-group form-control-default">
                            <label for="page_id">URL</label>
                            <input type="text" placeholder="Value" class="form-control" name="footer_14_value" id="footer_14_value" value="{{ $footerSettings['footer_link_14']['value'] }}">
                        </div>

                        <!-- PAGE -->
                        <div class="form-group form-control-default">
                            <label for="page_id">Page <font color="red">(Overrides URL)</font></label>
                            <select style="width: 100%;" data-toggle="select2" name="page_id" id="footer_14_page" data-placeholder="Select Landing Page .." data-allow-clear="true">
                                <option></option>
                                @foreach ($pages as $item)
                                <option value="{{ $item['page_id'] }}" @if($item['page_id'] == $footerSettings['footer_link_14']['page_id']) selected @endif> {{ $item['title'] }} </option>
                                @endforeach    
                            </select>
                        </div>

                        <!-- SAVE BUTTON -->
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="saveFooterLink(14)">Save</button>
                        </div>
                    </div>  
                </div>
                <!-- // END Panes -->

            </div>
            <!-- // END Tabbable Widget -->
@endsection

@section('scripts')
    <script src="{{asset('admin_assets/js/jq-ajax-progress.js')}}"></script>
    <script>
        // CHANGE SAVE FOOTER
        function saveFooterLink( identifier ){
            let data = {};
            data.link_num = identifier;

            // FETCH LABEL
            let label = $(`#footer_${identifier}_label`).val();
            data.label = label;

            //FETCH VALUE
            let value = $(`#footer_${identifier}_value`).val();
            data.value = value;

            //PAGE 
            if($(`#footer_${identifier}_page`).select2('data'))
            {
                let pageId = $(`#footer_${identifier}_page`).select2('data').id;
                data.page_id = pageId;
            }

            //SEND AJAX
            $.ajax({
                method: 'POST',
                dataType: 'json',
                url: "{{ route('save_footer_link') }}",
                data: data,
            }).done(function( data ) {
                alert(`Footer ${identifier} Is Updated`);
            }).fail(function( jqXHR, textStatus ) {
                alert(`Could Not Update Footer ${identifier}`);
            });
        }
    </script>
@stop