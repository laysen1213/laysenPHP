<template>
    <div>
		<left></left>
		<div class="c_right">
			<router-view></router-view>
		</div>
	</div>
</template>
<script type="text/javascript">
import left from '@/view/left'
export default {
	name: 'mains',
	components: {
	    left
	},
}
</script>