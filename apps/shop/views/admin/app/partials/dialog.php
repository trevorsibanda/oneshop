<div class="modal-header">
    <h3 class="modal-title"><i class="fa fa-{{ vm.type }} "></i> <span class="text-center" ng-bind="vm.heading"></span></h3>
</div>
<div class="modal-body">
    <h4 class="text-center" ng-bind="vm.prompt" ></h4>
    <br/>
    <div class="">
    	<p>
    		{{ vm.message }}
    	</p>
    </div>
</div>
<div class="modal-footer" >
	<div class="pull-right">
		<button class="btn btn-lg btn-success" ng-click="vm.select(true)"><i class="fa fa-check"></i> Yes</button>
		<button class="btn btn-lg btn-warning" ng-click="vm.select(false)"><i class="fa fa-times"></i> No</button>
	</div>
</div>    