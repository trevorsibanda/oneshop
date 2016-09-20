    <script type="text/ng-template" id="filemanager.html">
        <div class="modal-header">
            <h3 class="modal-title">File Manager<a ng-click="vm.close()"><span class="close"  >&times;</span></a></h3>
            
        </div>
        <div class="modal-body">
            <tabset justified="true" type="pills">
                <br/>
                <!-- Select File -->
                <tab ng-if="vm.selectFile">
                    <tab-heading>
                        <i class="fa fa-bell"></i> Select File
                    </tab-heading>
                    <div class="row" >
                        <div class="col-sm-6" >
                            <h3>Select File</h3>
                        </div>
                        
                        <div class="col-sm-6" >
                            <label>Filter</label>
                            <div class="input-group" >
                                <input type="text" class="form-control" ng-model="vm.file_search" />
                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                            </div>
                        </div>    
                    </div>
                    <style>
                        .selected{
                         border: solid blue 3px;
                        }

                        .filebox
                        {
                            min-height: 150px;
                        }

                        .filebox:hover{
                            background: rgba(250,250,250, 0.9);
                            border: solid 1px #deadbe;
                        }

                    </style>
                    <div class="container" style="max-height: 400px; overflow-y: scroll;"  >
                        
                        <div class="row">
                            <div class="col-sm-3 " >
                                <label>Select Folder</label>
                                <div class="btn-group btn-block">
                                    <label class="btn btn-default btn-block" ng-show="vm.showProductImage" ng-model="vm.type" btn-radio="'product_image'"><i class="fa fa-folder"></i> Product Images</label>
                                    <label class="btn btn-default btn-block" ng-show="vm.showShopImage" ng-model="vm.type" btn-radio="'shop_image'"><i class="fa fa-folder"></i> Shop Images</label>
                                    <label class="btn btn-default btn-block" ng-show="vm.showUserImage" ng-model="vm.type" btn-radio="'user_image'"><i class="fa fa-folder"></i> User Images</label>
                                    <label class="btn btn-default btn-block" ng-show="vm.showProductFile" ng-model="vm.type" btn-radio="'product_file'"><i class="fa fa-folder"></i> Product Files</label>
                                </div>
                            </div>
                            <div class="col-sm-9" >
                                <div class="col-md-4 col-sm-6 "  style="margin-top: 5px;"  ng-repeat="file in vm.files | filter:vm.file_search " >
                                    <div class="filebox well" >
                                    <a ng-click="vm.select( vm.id(file) )"  popover-trigger="mouseenter" popover="{{ file.meta }} . {{file.ext}} File size {{ file.bytes/1024| number:2 }}Kb" >
                                        <img ng-hide="vm.showProductFile" alt="{{ file.meta + ' ' + file.filename + ' | ' + file.ext }}" ng-class="{  'selected' : vm.isSelected( vm.id( file ) )}" ng-src="{{ vm.image_url( file ) }}" class="thumbnail" style="width: 100%; height: 160px;">
                                        <div ng-show="vm.showProductFile" class="text-center" >
                                            <span class="fa fa-5x fa-file-o" style="text-align: center;" ></span>
                                            <br/>
                                            <strong>{{ file.filename }} <br/>{{file.ext | uppercase }}</strong><br/>
                                            <small>{{ file.bytes/1024 | number:2 }}Kbytes</small>
                                        </div>   
                                    </a>
                                    
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>   
                </tab>
                <!-- End Select File -->
                
                <!-- File Manager -->
                <tab ng-if="vm.fileManager" >
                    <tab-heading>
                        <i class="fa fa-file-o" ></i> File Manager
                    </tab-heading>
                    <div class="row" >
                        <div class="col-xs-6" >
                            <h3>Select File</h3>
                        </div>
                        
                        <div class="col-xs-6" >
                            <label>Filter</label>
                            <div class="input-group" >
                                <input type="text" class="form-control" ng-model="vm.file_search" />
                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                            </div>
                        </div>    
                    </div>
                    <style>
                        .selected{
                         border: solid blue 3px;
                        }

                        .filebox
                        {
                            min-height: 150px;
                        }

                        .filebox:hover{
                            background: rgba(250,250,250, 0.9);
                            border: solid 1px #deadbe;
                        }

                    </style>
                    <div class="container"   >
                        
                        <div class="row">
                            <div class="col-xs-3 " >
                                <label>Select Folder</label>
                                <div class="btn-group btn-block">
                                    <label class="btn btn-default btn-block" ng-show="vm.showProductImage" ng-model="vm.type" btn-radio="'product_image'"><i class="fa fa-folder"></i> Product Images</label>
                                    <label class="btn btn-default btn-block" ng-show="vm.showShopImage" ng-model="vm.type" btn-radio="'shop_image'"><i class="fa fa-folder"></i> Shop Images</label>
                                    <!--
                                    <label class="btn btn-default btn-block" ng-show="vm.showUserImage" ng-model="vm.type" btn-radio="'user_image'"><i class="fa fa-folder"></i> User Images</label>
                                    -->
                                    <label class="btn btn-default btn-block" ng-show="vm.showProductFile" ng-model="vm.type" btn-radio="'product_file'"><i class="fa fa-folder"></i> Product Files</label>
                                </div>
                            </div>
                            <div class="col-xs-9" style="max-height: 400px; overflow-y: scroll;" >
                                <div class="col-md-4 col-sm-6 "  style="margin-top: 5px;"  ng-repeat="file in vm.files | filter:vm.file_search " >
                                    <div class="filebox well" >
                                    <a  popover-trigger="mouseenter" popover="{{ file.meta }} . {{file.ext}} File size {{ file.bytes/1024| number:2 }}Kb" >
                                        <img ng-hide="vm.isFile( file )" alt="{{ file.meta + ' ' + file.filename + ' | ' + file.ext }}" ng-class="{  'selected' : vm.isSelected( vm.id( file ) )}" ng-src="{{ vm.image_url(file) }}" class="thumbnail" style="width: 100%; height: 160px;">
                                        <div ng-show="vm.isFile( file )" class="text-center" >
                                            <span class="fa fa-5x fa-file-o" style="text-align: center;" ></span>
                                            <br/>
                                            <strong>{{ file.filename }} <br/>{{file.ext | uppercase }}</strong><br/>
                                            <small>{{ file.bytes/1024 | number:2 }}Kbytes</small>
                                            
                                        </div>

                                    </a>
                                    <button class="btn btn-block btn-xs btn-info " ng-click="vm.editFile(file)"><i class="fa fa-pencil"></i> Edit {{  vm.isFile( file ) ? 'Filename' : 'Meta Data' }}</button>
                                    <button class="btn btn-block btn-xs btn-danger " ng-click="vm.deleteFile(file)"><i class="fa fa-remove"></i> Delete File</button>
                                    
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>   

                </tab>
                <!-- End file manager -->
                <!-- Upload File -->
                <tab ng-if="vm.uploadFile">
                    <tab-heading>
                        <i class="fa fa-upload" ></i> Upload File
                    </tab-heading>      
                    <div class="container">
                        <div class="row">

                            <div class="col-md-3">

                                <label>Upload into folder</label>
                                <select ng-hide="vm.restrictUpload" class="form-control" ng-model="vm.uploadType" >
                                    <option value="product_image">Product Images</option>
                                    <option value="shop_image">Shop/Blog Images</option>
                                    <!--
                                    <option value="user_image">Shop User Images</option>
                                    -->
                                    <option value="product_file">Product Files</option>
                                </select>

                                <div ng-show="vm.uploader.isHTML5">
                                    <!-- 3. nv-file-over uploader="link" over-class="className" -->
                                    <div class="well my-drop-zone" style="height: 75px;" nv-file-over="" uploader="vm.uploader">
                                        Drop your files here !
                                    </div>

                                    
                                </div>
                                <span class="btn btn-success btn-block btn-file">
                                    Select file <input type="file" nv-file-select=""  uploader="vm.uploader" multiple  >
                                </span>
                                
                            </div>

                            <div class="col-md-9" style="margin-bottom: 40px">
                                
                                <h4>Upload your {{ vm.humanType(vm.uploadType) }}</h4>
                                <p>Allowed types are {{ vm.uploadFilter | uppercase }}.<br/>
                                 After uploading a file you can edit it's meta data.<br/>
                                Uploading {{ vm.uploader.queue.length }} files</p>
                                <div class="row" style="max-height: 400px; overflow-y: scroll;" >
                                    <div class="col-xs-6 col-sm-4" ng-repeat="item in vm.uploader.queue" >
                                        <div class="filebox alert alert-info"  popover="{{ item.file.name }} {{ item.file.size/1024/1024|number:2 }} MB" popover-trigger="mouseenter" >
                                            <a ng-click="item.remove()" title="Remove this file from the upload" class="close" ><i class="fa fa-remove"></i></a>
                                            
                                            <div ng-show="vm.uploader.isHTML5" ng-thumb="{ file: item._file, height: 150 }"></div>
                                            <progressbar max="100" value="item.progress"><span style="color:black; white-space:nowrap;">Uploading {{item.progress}}% ...</span></progressbar>
                                            
                                            <button type="button" class="btn btn-info btn-block btn-xs" ng-click="item.upload()" ng-hide="item.isReady || item.isUploading || item.isSuccess">
                                                <span class="glyphicon glyphicon-upload"></span> Upload
                                            </button>
                                            <button type="button" class="btn btn-success btn-block btn-xs" ng-click="vm.select( vm.id(item.savedFile) )" ng-show=" item.savedFile ">
                                                <span class="glyphicon glyphicon-upload"></span> Select Image
                                            </button>
                                            <button type="button" ng-if="! item.isUploading " class="btn btn-warning btn-block btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                                                <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                                            </button>
                                            
                                            
                                        </div>

                                    </div>
                                </div>
                                

                                <div>
                                    <div>
                                        Queue progress:
                                        <div class="progress" style="">
                                            <div class="progress-bar" role="progressbar" ng-style="{ 'width': vm.uploader.progress + '%' }"></div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success btn-s" ng-click="vm.uploader.uploadAll()" ng-disabled="!vm.uploader.getNotUploadedItems().length">
                                        <span class="glyphicon glyphicon-upload"></span> Upload all
                                    </button>
                                    <button type="button" class="btn btn-warning btn-s" ng-click="vm.uploader.cancelAll()" ng-disabled="!vm.uploader.isUploading">
                                        <span class="glyphicon glyphicon-ban-circle"></span> Cancel all
                                    </button>
                                    <button type="button" class="btn btn-danger btn-s" ng-click="vm.uploader.clearQueue()" ng-disabled="!vm.uploader.queue.length">
                                        <span class="glyphicon glyphicon-trash"></span> Remove all
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>
      
                </tab>
                <!-- End upload file -->
            </tabset>
        </div>
    
    </script>