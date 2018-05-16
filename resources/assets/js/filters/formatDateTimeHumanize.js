import moment from 'moment'

export default function (date) {
	if(date) {
		var start = moment(date, 'YYYY-MM-DD HH:mm:ss')
		return start.startOf().locale('vi').fromNow()
	}
}