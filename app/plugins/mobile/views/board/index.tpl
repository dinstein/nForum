<{include file="../plugins/mobile/views/header.tpl"}>
<div class="sec nav">
<{if $canPost}>
	<a href="<{$mbase}>/article/<{$bName}>/post<{if !$threads}>?m=1<{/if}>">����</a>|
<{/if}>
<{if $threads}>
	<a href="<{$mbase}>/board/<{$bName}>/0">����</a>|
<{else}>
	<a href="<{$mbase}>/board/<{$bName}>">ͬ����</a>|
<{/if}>
</div>
<ul class="list sec">
<{if $info}>
<{foreach from=$info item=item key=k}>
<li<{cycle values=', class="hla"'}>>
<{if $threads}>
		<a href="<{$mbase}>/article/<{$bName}>/<{$item.gid}>"<{if $item.tag}> class="<{$item.tag}>"<{/if}>><{$item.title}></a>(<{$item.num}>)<br />
		<{$item.postTime}>&nbsp;<a href="<{$mbase}>/user/query/<{$item.poster}>"><{$item.poster}></a>|
		<{$item.replyTime}>&nbsp;<a href="<{$mbase}>/user/query/<{$item.last}>"><{$item.last}></a>
<{else}>
		<a href="<{$mbase}>/article/<{$bName}>/single/<{$item.gid}>"<{if $item.tag}> class="<{$item.tag}>"<{/if}>><{if $item.subject}>��&nbsp;<{/if}><{$item.title}></a><{if $threads}>(<{$item.num}>)<{/if}><br />
		<{if $item.top}>[��ʾ]<{else}><{$item.pos}><{/if}>&nbsp;<{$item.postTime}>&nbsp;<a href="<{$mbase}>/user/query/<{$item.poster}>"><{$item.poster}></a>
<{/if}>
</li>
<{/foreach}>
<{else}>
<li>�ð���û���κ�����</li>
<{/if}>
</ul>
<div class="sec nav">
<form action="<{$mbase}>/board/<{$bName}><{if !$threads}>/<{$mode}><{/if}>" method="get">
<{if $curPage != 1}>
	<a href="<{$mbase}>/board/<{$bName}><{if !$threads}>/<{$mode}><{/if}>?p=1">��ҳ</a>|

	<a href="<{$mbase}>/board/<{$bName}><{if !$threads}>/<{$mode}><{/if}>?p=<{$curPage-1}>">��ҳ</a>|
<{/if}>
<{if $curPage != $totalPage}>
	<a href="<{$mbase}>/board/<{$bName}><{if !$threads}>/<{$mode}><{/if}>?p=<{$curPage+1}>">��ҳ</a>|
	<a href="<{$mbase}>/board/<{$bName}><{if !$threads}>/<{$mode}><{/if}>?p=<{$totalPage}>">βҳ</a>|
<{/if}>
	<a class="plant"><{$curPage}>/<{$totalPage}></a>|
	<a class="plant">ת��<input type="text" name="p" size="2" /><input type="submit" value="GO" class="btn" /></a>|
</form>
</div>
<{include file="../plugins/mobile/views/footer.tpl"}>