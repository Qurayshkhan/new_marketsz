export default {
    methods: {
        __format_date_time(date) {
            try {
                const parsedDate = new Date(date);
                if (isNaN(parsedDate)) return "";

                const options = { day: '2-digit', month: 'short', year: 'numeric' };
                return new Intl.DateTimeFormat('en-US', options).format(parsedDate);

            } catch (error) {
                console.log("🚀 ~ __format_date_time ~ error:", error);
            }
        },
        __to_fixed_number(num) {
            if (num) {
                return Number(num)?.toFixed(2);
            } else {
                return "0.00";
            }
        }
    },
}
