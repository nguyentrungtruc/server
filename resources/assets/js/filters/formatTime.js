import moment from 'moment'

export default function (time) {
	if(time) {
		return moment(time,'HH:mm:ss').format('HH:mm')
	}
}