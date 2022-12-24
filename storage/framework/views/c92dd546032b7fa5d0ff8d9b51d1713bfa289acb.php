<?php $__env->startSection('title'); ?>
    404
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Page not found!</h1>
    <p>
    The requested page could not be found. Apologies for the inconvenience.
    <br/><samp class="blue"><?php echo rawurldecode(URL::current()); ?></samp>
    <br/>
    <br/>

    <?php echo Form::open(array('action'=>'ElasticSearchPageController@getIndex', 'method'=>'GET', 'id'=>'search', 'class'=>'form-horizontal', 'autocomplete'=>'on')); ?>

    <?php echo Form::label('q', 'You could try to search for the information you were looking for:', array('for'=>'query')); ?>

    <?php echo Form::text('q', $q, array('id'=>'q', 'placeholder'=>'Enter keywords...', 'class'=>'form-control')); ?>

    <?php echo Form::hidden('source', '404'); ?>

    <?php echo Form::hidden('url', rawurldecode(URL::current())); ?>

    <br/>
    Please <a href="/en/contact">contact</a> us if you think something is missing or goto the <a href="/en/helpfaq">help/FAQ</a> section.

    <hr class="thin"/>
    <?php echo Form::button(trans('locale.button.search'), array('type'=>'submit', 'class'=>'btn btn-default btn-black')); ?>

    <?php echo Form::close(); ?>

    </p>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('site.layout.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>