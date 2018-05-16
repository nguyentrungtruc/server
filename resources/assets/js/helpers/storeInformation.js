export function activity(value) {
	const _v = new String(value).toLowerCase();
	switch(value) {
		case 'cả ngày': 
		return 1
		break;
		case 'hai ca':
		return 2
		break;
		case 'ba ca':
		return 3 
		break;
	}
}
export function percent(value) {
	const percent = parseInt(value)
	return percent+ '%'
}