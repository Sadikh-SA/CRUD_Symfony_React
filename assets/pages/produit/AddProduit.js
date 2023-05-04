import React, {useState} from 'react';
import { Link } from "react-router-dom";
import Layout from "../../components/Layout"
import Swal from 'sweetalert2'
import axios from 'axios';
function AddProduit() {
    const [nom, setNom] = useState('');
    const [code, setCode] = useState('')
    const [date_peremtion, setDate_peremtion] = useState('')
    const [prix_achat, setPrix_achat] = useState('')
    const [prix_vente, setPrix_vente] = useState('')
    const [date_fabrication, setDate_fabrication] = useState('')
    const [categorie, setCategorie] = useState('')
    const [fournisseur, setFournisseur] = useState('')
    const [isSaving, setIsSaving] = useState(false)
const saveRecord = () => {
        setIsSaving(true);
        let formData = new FormData()
        formData.append("nom", nom)
        formData.append("code", code)
        formData.append("prix_achat", prix_achat)
        formData.append("prix_vente", prix_vente)
        formData.append("date_peremtion", date_peremtion)
        formData.append("date_fabrication", date_fabrication)
        formData.append("categorie", categorie)
        formData.append("fournisseur", fournisseur)
        if(nom == "" || code == "" || prix_achat==""){
            Swal.fire({
                icon: 'error',
                title: 'Name, code, prix_achat are required fields.',
                showConfirmButton: true,
                showCloseButton: true,
            })
            setIsSaving(false)
        }else{
            axios.post('/api/projet/produit', formData)
              .then(function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Produit has been added successfully!',
                    showConfirmButton: true,
                })
                setIsSaving(false);
                setNom('')
                setPrix_achat('')
                setCode('')
                setDate_peremtion('')
                setDate_fabrication('')
                setPrix_vente('')
                setCategorie('')
                setFournisseur('')
              })
              .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops, Something went wrong!',
                    showConfirmButton: true,
                    
                })
                setIsSaving(false)
              });
        }
    }
    return (
        <Layout>
            <div className="container">
                <h2 className="text-center mt-5 mb-3">Add Produit</h2>
                <div className="card">
                    <div className="card-header">
                        <Link 
                            className="btn btn-info float-left"
                            to="/">Back To Produit List
                        </Link>
                    </div>
                    <div className="card-body">
                        <form>
                            <div className="form-group">
                                <label htmlFor="name">Name</label>
                                <input 
                                    onChange={(event)=>{setNom(event.target.value)}}
                                    value={nom}
                                    type="text"
                                    className="form-control"
                                    id="nom"
                                    name="nom" required/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="name">code</label>
                                <input 
                                    onChange={(event)=>{setCode(event.target.value)}}
                                    value={code}
                                    type="text"
                                    className="form-control"
                                    id="code"
                                    name="code" required/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="prix_achat">prix_achat</label>
                                <input 
                                    onChange={(event)=>{setPrix_achat(event.target.value)}}
                                    value={prix_achat}
                                    type="double"
                                    className="form-control"
                                    id="prix_achat"
                                    name="prix_achat" required/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="date_peremtion">date_peremtion</label>
                                <input 
                                    onChange={(event)=>{setDate_peremtion(event.target.value)}}
                                    value={date_peremtion}
                                    type="date"
                                    className="form-control"
                                    id="date_peremtion"
                                    name="date_peremtion" required/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="date_fabrication">date_fabrication</label>
                                <input 
                                    onChange={(event)=>{setDate_fabrication(event.target.value)}}
                                    value={date_fabrication}
                                    type="date"
                                    className="form-control"
                                    id="date_fabrication"
                                    name="date_fabrication" required/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="prix_vente">prix_vente</label>
                                <input 
                                    onChange={(event)=>{setPrix_vente(event.target.value)}}
                                    value={prix_vente}
                                    type="text"
                                    className="form-control"
                                    id="prix_vente"
                                    name="prix_vente" required/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="categorie">categorie</label>
                                <input 
                                    onChange={(event)=>{setCategorie(event.target.value)}}
                                    value={categorie}
                                    type="text"
                                    className="form-control"
                                    id="categorie"
                                    name="categorie" required/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="fournisseur">fournisseur</label>
                                <input 
                                    onChange={(event)=>{setFournisseur(event.target.value)}}
                                    value={fournisseur}
                                    type="text"
                                    className="form-control"
                                    id="fournisseur"
                                    name="fournisseur" required/>
                            </div>
                            <button 
                                disabled={isSaving}
                                onClick={saveRecord} 
                                type="button"
                                className="btn btn-primary mt-3">
                                Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
export default AddProduit;