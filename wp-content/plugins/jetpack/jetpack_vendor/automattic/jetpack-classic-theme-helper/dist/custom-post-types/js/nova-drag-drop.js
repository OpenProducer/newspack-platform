!function(e){let n;e(document).ready((function(){n=e("#the-list"),n.sortable({cancel:".no-items, .inline-edit-row",stop:function(t,r){if(r.item.is(":first-child"))return n.sortable("cancel");n.find(".menu-label-row").each((function(){const n=e(this).data("term_id");e(this).nextUntil(".menu-label-row").each((function(t){const r=e(this);r.find(".menu-order-value").val(t),r.find(".nova-menu-term").val(n)}))}))}}),e("#posts-filter").append('<input type="hidden" name="'+_novaDragDrop.nonceName+'" value="'+_novaDragDrop.nonce+'" />'),e(".tablenav").prepend('<input type="submit" class="button-primary button-reorder alignright" value="'+_novaDragDrop.reorder+'" name="'+_novaDragDrop.reorderName+'" />'),e("#posts-filter").attr("method","post")}))}(jQuery);