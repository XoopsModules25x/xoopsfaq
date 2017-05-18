<{if !empty($block)}>
<article>
<dl>
<{foreach from=$block.faq item=faq}>
  <dt><{$faq.title}><{if 1 == $block.show_date}>&nbsp;<span class="x-small">(<{$faq.published}>)</span><{/if}></dt>
  <dd><{$faq.ans}></dd>
<{/foreach}>
</dl>
</article>
<{/if}>
