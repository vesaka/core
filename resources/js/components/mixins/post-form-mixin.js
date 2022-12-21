import Description from '@core/components//post/attributes/Description';
import Tags from '@core/components//post/attributes/Tags';
import Category from '@core/components//post/attributes/Category';
import FeaturedImage from '@core/components//post/attributes/FeaturedImage';
import Gallery from '@core/components//post/attributes/Gallery';
import Bus from '@/utils/bus';
export default {
    data() {
        return {
            post: {
                id: '',
                name: '',
                slug: '',
                content: '',
                fileIds: []
            }
        };
    },
    props: {
        type: {
            type: String,
            default: 'post'
        }
    },
    components: {Description, Tags, Category, FeaturedImage},
    computed: {
        
    },
    methods: {
        save() {

        },
        addUploadedFile(data) {
            
            if (!this.post.fileIds) {
                this.post.fileIds = []
            }
            this.post.fileIds.push(data.data);
            console.log(this.post.fileIds);
            
        }
    },
    created() {
        Bus.$on('file-uploaded', this.addUploadedFile);
    }
}