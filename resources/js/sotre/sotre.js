import { createStore  } from 'vuex';
import Axios from '../axios';

import FETCHING from "../constant/fetch"


const State = {
    User: null,
    isUserStateFetched: FETCHING.NO_FETCH,
    isUserRegisterStateFetched: FETCHING.NO_FETCH,

    Books: null,
    isBooksStateFetched:  FETCHING.NO_FETCH,

    isNewBooksStateFetched: FETCHING.NO_FETCH
}

const Mutations = {
    setUser (state, user) {
        state.User = user;
      },

    setUserStateFetched (state, fetched) {
        state.isUserStateFetched = fetched;
    },
    setUserRegisterStateFetched (state, fetched) {
        state.isUserRegisterStateFetched = fetched;
    },

    setBooks (state, books) {
        state.Books = books;
    },

    setUserBookRate(state,{bookId,rate}) {
        for(const bookComment of state.User.comments) {
             if(bookComment.book_id === bookId) {
                bookComment.note = rate;
                return;
             }
        }
        state.User.comments.push( {
            "id": state.User.comments.length+1,
            "comment": "0",
            "note": rate,
            "user_id": state.User.id,
            "book_id": bookId,
            "comment_updated_at": (new Date()).toISOString()
        })
    },

    removeItemBook(state,IndexToRemove)
    {
        state.Books.splice(IndexToRemove,1)
    },

    pushBookInUser(state,book)
    {
        state.User.books.push(book)
    },

    pushBook(state,book)
    {
        state.Books.push(book)
        state.Books.sort((a, b) => {
            const nameA = a.title.toLowerCase();
            const nameB = b.title.toLowerCase();

            if (nameA < nameB) return -1;
            if (nameA > nameB) return 1;
            return 0;
        });
    },

    sortBooks(state) {
        state.Books.sort((a, b) => {
            const nameA = a.title.toLowerCase();
            const nameB = b.title.toLowerCase();

            if (nameA < nameB) return -1;
            if (nameA > nameB) return 1;
            return 0;
        });
    },

    removeItemBookInUser(state,IndexToRemove)
    {
        state.User.books.splice(IndexToRemove,1)
    },

    setBooksStateFetched (state, fetched) {
        state.isBooksStateFetched = fetched;
    },

    setNewBooksStateFetched (state, fetched) {
        state.isNewBooksStateFetched = fetched;
    }
}

const Actions = {
    async loadUser({ dispatch, commit,state })
    {
        if(state.User) {
           return true;
        }

        commit("setUserStateFetched",FETCHING.STARTED_FETCH);
        const userResponse = await Axios.get("/user");

      //  await await new Promise(r => setTimeout(r, 2000));

        if(userResponse.data?.id)
        {
           commit("setUser",userResponse.data);
           commit("setUserStateFetched",FETCHING.SUCCESS_FETCH);
           return true;
        }
        commit("setUserStateFetched",FETCHING.FAILED_FETCH);
        return false;
    },

    async loadBooks({ dispatch, commit,state })
    {
        commit("setBooksStateFetched",FETCHING.STARTED_FETCH);
        const userResponse = await Axios.get("/books");

        console.log("user userResponse = ",userResponse);

       //  await await new Promise(r => setTimeout(r, 2000));

         if(Array.isArray(userResponse.data))
         {
            commit("setBooks",userResponse.data);
            commit("sortBooks");
            commit("setBooksStateFetched",FETCHING.SUCCESS_FETCH);
            return true;
         }
         commit("setBooksStateFetched",FETCHING.FAILED_FETCH);
         return false;
    },

    async addLibraryBook({ dispatch, commit, state },book) {
        commit("setNewBooksStateFetched",FETCHING.STARTED_FETCH);
        await Axios.post(`/user/add/library/book`,book);
        commit("setNewBooksStateFetched",FETCHING.SUCCESS_FETCH);
        return true;
    },

    async addBookInUserLibrary({ dispatch, commit, state },bookId) {
        commit("setBooksStateFetched",FETCHING.STARTED_FETCH);
        await Axios.post(`/user/add/book`,{bookId: bookId});
        commit("setBooksStateFetched",FETCHING.SUCCESS_FETCH);
        return true;
    },

    async setUserBookRate({ dispatch, commit, state },{bookId,rate}) {
        commit("setBooksStateFetched",FETCHING.STARTED_FETCH);
        await Axios.post(`/user/add/book/comment`,{bookId,rate,comment:null});
        commit("setBooksStateFetched",FETCHING.SUCCESS_FETCH);
        return true;
    },


    async removeBookInUserLibrary({ dispatch, commit, state },bookId) {
        commit("setBooksStateFetched",FETCHING.STARTED_FETCH);
        await Axios.delete(`/user/book/${bookId}`);
        commit("setBooksStateFetched",FETCHING.SUCCESS_FETCH);
        return true;
    },

    async register({ dispatch, commit, state },auth) {

        commit("setUserRegisterStateFetched",FETCHING.STARTED_FETCH);
        const userResponse = await Axios.post("/register",auth);

        console.log(" register userResponse.data = ",auth);

        if(!userResponse.data?.error)
        {
           commit("setUser",userResponse.data);
           commit("setUserRegisterStateFetched",FETCHING.SUCCESS_FETCH);
           return { success: true, data: state.User};
        }
        commit("setUserRegisterStateFetched",FETCHING.FAILED_FETCH);
        return { success: false, data: userResponse.data.error};
    },

    async login({ dispatch, commit,state },auth)
    {
        commit("setUserStateFetched",FETCHING.STARTED_FETCH);
        const userResponse = await Axios.post("/auth",auth);

    //    await await new Promise(r => setTimeout(r, 2000));

    if(!userResponse.data?.error)
        {
           commit("setUser",userResponse.data);
           commit("setUserStateFetched",FETCHING.SUCCESS_FETCH);
           return { success: true, data: state.User};
        }
        commit("setUserStateFetched",FETCHING.FAILED_FETCH);
        return { success: false, data: userResponse.data.error};
    },

    async logout({ dispatch, commit })
    {
        await Axios.post("/logout");

        commit("setUser",null);
        return true;

    }
}

const Getters = {
  User(state) {
    return state.User;
  },
  isUserStateFetched(state) {
    return state.isUserStateFetched;
  },
  isUserRegisterStateFetched(state) {
    return state.isUserRegisterStateFetched;
  },
  Books(state) {
    return state.Books;
  },
  isBooksStateFetched(state) {
    return state.isBooksStateFetched;
  },
  isNewBooksStateFetched(state) {
    return state.isNewBooksStateFetched;
  },
}


const store = createStore({
    state () {
        return State;
    },
    mutations: Mutations,
    actions: Actions,
    getters: Getters
  })

export default store;
