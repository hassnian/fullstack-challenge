import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import {
  QueryClient,
  QueryClientProvider,
} from '@tanstack/react-query'
import { ReactQueryDevtools } from '@tanstack/react-query-devtools'

//pages
import HomePage from "./pages/HomePage"
import PrivateRoutes from "./components/common/PrivateRoutes"
import Login from "./pages/Login";
import SignUp from "./pages/SignUp";
import './index.css'

import AuthProvider from "./context/AuthProvider";
import UserPrefrences from "./pages/UserPrefrences";
import FeedPage from "./pages/FeedPage";

const queryClient = new QueryClient()

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <Router>
          <Routes>

            <Route element={<PrivateRoutes />}>
              <Route path='/user' element={<UserPrefrences />} />
              <Route path='/feed' element={<FeedPage />} />
            </Route>

            <Route path="/" element={<HomePage />} exact />
            <Route path='/login' element={<Login />} />
            <Route path='/register' element={<SignUp />} />

            <Route path="*" element={<h1>404</h1>} />
          </Routes>
        </Router>
      </AuthProvider>

      <ReactQueryDevtools initialIsOpen={false} />
    </QueryClientProvider>
  );
}

export default App;
