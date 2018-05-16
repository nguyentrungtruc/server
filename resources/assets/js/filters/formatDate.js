import moment from 'moment'

export default function (date) {
	if(date) {
		return moment(date, 'YYYY-MM-DD').format('DD-MM-YYYY')
	}
}