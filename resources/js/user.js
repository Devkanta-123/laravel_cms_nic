import { reactive, toRefs } from 'vue';
import axios from 'axios';

const state = reactive({
    user: null,
    role: null,
    website: null
});

const setUser = (user, website) => {
    state.user = user;
    state.role = user.role_id;
    state.website = website.website_short;
};

const fetchUser = async () => {
    try {
        await axios.get('/api/get_user')  
        .then((response) => {
            console.log("Login User Data" , response.data[0]);
            setUser(response.data[0], response.data[1][0]);
        })  
        .catch((error) => {
          console.error('Error:', error);
        });

      
    } catch (error) {
        console.error('Failed to fetch user:', error);
    }
};


export default function useUser() {
    return {
        ...toRefs(state),
        fetchUser
    };
}