import { Navigate, Outlet } from 'react-router-dom'
import { useAuth } from '../../hooks/use-auth'

const PrivateRoutes = () => {
    const { isSignedIn } = useAuth()

    return (
        isSignedIn ? <Outlet /> : <Navigate to={'/login'} />
    )
}

export default PrivateRoutes