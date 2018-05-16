import numeral from 'numeral'

export default function (price) {
	if(price) {
		return numeral(price).format('0,0$')
	}
}