<?php if (!defined('THINK_PATH')) exit(); if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><dl>
    <dt class="hthin">
        <div class="row">
			<div class="col-sm-2 col-md-4 col-lg-1"><?php echo ($list['id']); ?></div>
            <div class="col-sm-2 col-md-4 col-lg-1 fold"><i class="fa"></i></div>
            <div class="col-sm-6 col-md-6 col-lg-8">
                <span class="tab-sign pull-left"></span>
                <div class="col-lg-4"><?php echo ($list["title"]); ?></div>
                <a class="color-success add-sub md-trigger" data-toggle="tooltip" data-original-title="增加子栏目" title="增加子栏目" url="<?php echo U('Category/add?pid='.$list['id']);?>" href="#">
                <i class="fa fa-plus-circle"></i>增加子栏目
                </a>
            </div>
            <div class="col-sm-4 col-md-4 col-lg-2 text-right">
                <a data-placement="left" data-toggle="tooltip" data-original-title="修改栏目" class="label label-primary md-trigger" url="<?php echo U('Category/edit?id='.$list['id'].'&pid='.$list['pid']);?>" href="#"><i class="fa fa-pencil"></i></a>
                <a data-placement="left" data-toggle="tooltip" data-original-title="移动栏目" class="label label-info md-triggers" url="<?php echo U('operate?type=move&from='.$list['id']);?>" href="#"><i class="fa fa-random"></i></a>
                <a data-placement="left" data-toggle="tooltip" data-original-title="合并栏目" class="label label-success md-triggers" url="<?php echo U('operate?type=merge&from='.$list['id']);?>" href="#"><i class="fa fa-retweet"></i></a>
                <a data-placement="left" data-toggle="tooltip" data-original-title="删除栏目" class="label label-danger ajax-get" href="<?php echo U('Category/remove?id='.$list['id']);?>"><i class="fa fa-times"></i></a>
            </div>
        </div>
    </dt>
    <?php if(!empty($list['_'])): ?><dd>
		<?php echo R('Category/tree', array($list['_']));?>
    </dd><?php endif; ?>
</dl><?php endforeach; endif; else: echo "" ;endif; ?>