import Spinner from '$m/media/game/components/common/elements/Spinner';

export default {
        components: {Spinner},
        computed: {
            loadingClass() {
                return {
                    'flex items-center': true,
                    'absolute top-0 z-50': true,
                    'transform-all bg-blue-500 opacity-70': true,
                    'w-0 h-0 hidden': !this.load,
                    'w-full h-full': this.load
                };
            }
        }
}