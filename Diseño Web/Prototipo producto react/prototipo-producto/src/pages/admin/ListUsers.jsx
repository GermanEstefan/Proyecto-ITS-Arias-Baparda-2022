import { faPencil, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React from 'react'
import { useEffect } from 'react';
import { useState } from 'react';
import { fetchApi } from '../../API/api';
import ContainerBase from '../../components/admin/ContainerBase';

const ListUsers = () => {

    const [employees, setEmployees] = useState([]);
    const [loadingFlags, setLoadingFlags] = useState({ fetchingUsers: true });

    useEffect(() => {
        fetchApi('auth-employees.php', 'GET')
            .then(resp => {
                console.log(resp)
                setEmployees(resp)
                
            })
            .catch(err => {
                alert('Error interno');
                console.log(err);
            })
            .finally(() => setLoadingFlags({ fetchingUsers: false }))
    }, [])

    return (
        <ContainerBase>
            <section className="container_section list-users">
                {
                    loadingFlags.fetchingUsers
                        ? <span className='fetching-data-message'>Obteniendo usuarios ...</span>
                        : <>
                            <h1 className='title-page'>Usuarios</h1>
                            <table className='table-template'>
                                <tbody>
                                    <tr>
                                        <th>Id</th>
                                        <th>C.I</th>
                                        <th>Rol</th>
                                        <th>Email</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Telefono</th>
                                        <th>Direccion</th>
                                        <th>Estado</th>
                                        <th colSpan={2}>Controles</th>
                                    </tr>
                                    {
                                        employees.map(employe => (
                                            <tr key={employe.employee_user}>
                                                <td>{employe.employee_user}</td>
                                                <td>{employe.ci}</td>
                                                <td>{employe.employee_role}</td>
                                                <td>{employe.email}</td>
                                                <td>{employe.name}</td>
                                                <td>{employe.surname}</td>
                                                <td>{employe.phone}</td>
                                                <td>{employe.address}</td>
                                                <td>{employe.state}</td>
                                                <td className="controls-table"><FontAwesomeIcon icon={faPencil} /></td>
                                                <td className="controls-table"><FontAwesomeIcon icon={faTrash} /></td>
                                            </tr>
                                        ))
                                    }
                                </tbody>
                            </table>
                        </>
                }

            </section>
        </ContainerBase>
    )
}

export default ListUsers;
