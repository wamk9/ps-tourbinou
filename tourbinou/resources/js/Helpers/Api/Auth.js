import Api from '@/Helpers/Api/Api.js';
import Store from '@/Store';
import SystemVars from '@/Helpers/General/SystemVars';

let Auth = {
    async checkAuthentication() {
        if (route().current('pages.admin.*') && Store.getters.getToken != null) {
            const result = await Api.postAsync(route('api.admin.refresh'), { token: Store.getters.getToken });

            if (result.code == 200) {
                Store.dispatch('setToken', result.response.data.token);
                if (route().current('pages.admin.login')) {
                    window.location.href = route('pages.admin.tour.index');
                }
                return true;
            }
        }

        Store.dispatch('removeToken');
        window.location.href = route('pages.admin.login');
        return false;
    },
    async maintainOnLogin() {
        if (route().current('pages.admin.login') && Store.getters.getToken != null) {
            window.location.href = route('pages.admin.tour.index');
            return false;
        }

        return true;
    },
    async login(data) {
        const result = await Api.postAsync('/login', data);

        if (result.code == 200) {
            Store.dispatch('setToken', result.response.data.token).then(()=>{window.location.assign(route('pages.admin.tour.index'));});
            return true;
        }

        return false;
    },
    async logout() {
        const result = await Api.postAsync('/admin/logout', { token: Store.getters.getToken });
        Store.dispatch('removeToken');

        if (result.code == 200) {
            Store.dispatch('removeToken').then(() => {window.location.href = route('pages.admin.login'); });
        } else {
            console.error (result.response);
        }
    }
}

export default Auth;
