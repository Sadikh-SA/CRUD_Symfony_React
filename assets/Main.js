import React, {StrictMode} from 'react';
import {createRoot } from "react-dom/client";
import {BrowserRouter as Router, Routes, Route } from "react-router-dom";
import {ListProduit} from "./pages/produit/ListProduit"
import {EditProduit} from "./pages/produit/EditProduit"
import {ViewProduit} from "./pages/produit/ViewProduit"
import {AddProduit} from './pages/produit/AddProduit';
import { Login } from './pages/Login/login';
import { Register } from './pages/Login/register';
   
function Main() {
    return (
        <Router>
            <Routes>
                <Route path="/"  element={<Login/>} />
                <Route path="register"  element={<Register/>} />
            </Routes>
        </Router>
    );
}
export default Main;  
if (document.getElementById('app')) {
    const rootElement = document.getElementById("app");
    const root = createRoot(rootElement);
    root.render(
        <StrictMode>
            <Main />
        </StrictMode>
    );
}