import firebase from 'firebase/app'
import "firebase/analytics";
import 'firebase/firebase-messaging'

const firebaseConfig = {
    apiKey: "AIzaSyDlJjbLe9GcEOVSKpCnT3Xin1hF6Rj2AEc",
    authDomain: "xctp-copito.firebaseapp.com",
    projectId: "xctp-copito",
    storageBucket: "xctp-copito.appspot.com",
    messagingSenderId: "963411504247",
    appId: "1:963411504247:web:db4c03350fc1ca17c5ea72",
    measurementId: "G-2JWJTWW01W"
};

firebase.initializeApp(firebaseConfig)

const analytics = firebase.analytics();
export default firebase.messaging()
