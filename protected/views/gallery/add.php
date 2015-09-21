<script type="text/javascript" src="/3rd/swfupload/swfupload.js"></script>
<script type="text/javascript" src="/3rd/swfupload/handlers.js"></script>
<link href="/3rd/swfupload/swfupload.css" rel="stylesheet" type="text/css" />
<style>
.edui-default .edui-editor,.edui-default .edui-editor-toolbarboxouter{border-radius:0;}
</style>

<script type="text/javascript"  src="/js/gaga.js"></script>





<ol class="breadcrumb">
  <li><a href="/gallery/index">首页</a></li>
  <li><a href="/gallery/index">相册管理</a></li>
  <li class="active">新增图片</li>
</ol>



<div class="panel panel-primary">
  <!-- Default panel contents -->
  <div class="panel-heading">新增图片</div>




        <table class="table">
                <tr>
                <td>所属画室</td>

                        <td> <input type="text" name="slug" id="slug" >
</td>
                </tr>


        <tr>




          <tr>
             <td >类型</td>
            <td><select class="selectpicker tag" name="tag" id="tag">
			<option>素描</option>
			<option>色彩</option>
			<option>速写</option>
                 </select>
             </td>
        </tr>



                <td>图片</td>

                <td>
                        <div class="fluid" id="divFileProgressContainer1">

                        </div><!--  进度条容器  -->


                        <br /><p style="" id="thumb_upload_wp"><span id="spanButtonPlaceholder1"></span></p>
                        <p id="spanUpladErrorInfo1"></p>
                </td>


        </tr>

        </table>


         <div>

                <button class="btn btn-primary" onclick="save()"  > 保存</button>
        </div>

</div>

<script type="text/javascript">



var save = function() {

	var source = '';
	var thumb = '';

	$(".mini-image-view").each(function(){
   		thumb += $(this).attr("src") + ',';
		source += $(this).attr("source") + ',';
  	});

	var uploadDate = new Date();
	uploadDate = uploadDate.toLocaleString( );		
	var tag = $('.tag').val();
	if(tag == '素描')
		tag = 11;
	else if(tag == '速写')
		tag = 12;
	else if(tag == '色彩')
		tag = 10;



	var slug = document.getElementById("slug").value;	
        
	    $.ajax({
                dataType: "json",

                 data:{
			"date":uploadDate,
			"tag":tag,
			"slug":slug,
                        "thumb":thumb.substring(0,thumb.length-1),
                        "source":source.substring(0,source.length-1)
                },
                type: "POST",
                url: "/gallery/addPic",
                success: function() {
                      alert('success');
			history.back();
                }
            });

};



</script>

