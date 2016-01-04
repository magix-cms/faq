<form id="add_qa_page" class="forms_plugins_travel" method="post" action="">
    <fieldset>
        <legend>{#add_qa#|ucfirst}</legend>
        <div class="row">
            <div class="form-group col-xs-12 col-sm-2">
                <label for="iso">ISO</label>
                <input id="iso" class="form-control" type="text" value="{$iso|upper}" size="3" readonly="readonly" disabled="disabled">
            </div>
        </div>

        <div class="row">
            <div class="form-group col-xs-12">
                <label for="title">{#question#|ucfirst}&nbsp;*</label>
                <input id="title" class="form-control" type="text" size="150" value="" name="title" placeholder="{#question_ph#|ucfirst}" />
            </div>
        </div>
        <div class="row">
            <div class="form-group col-xs-12">
                <label for="content">{#answer#|ucfirst}</label>
                <textarea name="content" id="content" class="form-control mceEditor"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-xs-12">
                <input type="submit" class="btn btn-primary" value="{#save#|ucfirst}" />
                <input type="hidden" id="idqa" name="idqa" value="" />
            </div>
        </div>
    </fieldset>
</form>