import React, { useEffect, useState } from 'react';
import { ServerContext } from '@/state/server';
import ServerContentBlock from '@/components/elements/ServerContentBlock';
import tw from 'twin.macro';
import useSWR from 'swr';
import FlashMessageRender from '@/components/FlashMessageRender';
import searchPlugins from '@/api/server/plugins/searchPlugins';
import useFlash from '@/plugins/useFlash';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import Field from '@/components/elements/Field';
import { number, object, string } from 'yup';
import { Field as FormikField, Form, Formik, FormikHelpers } from 'formik';
import Spinner from '@/components/elements/Spinner';
import Select from '@/components/elements/Select';
import FormikFieldWrapper from '@/components/elements/FormikFieldWrapper';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faAngleDoubleLeft, faAngleDoubleRight, faStar, faExternalLinkAlt } from '@fortawesome/free-solid-svg-icons';
import styled from 'styled-components/macro';
import Button, { LinkButton } from '@/components/elements/Button';
import ManageButton from '@/components/server/plugins/ManageButton';

const Block = styled(Button)`
    ${tw`p-0 w-10 h-10`}
    
    &:not(:last-of-type) {
        ${tw`mr-2`};
    }
`;

export interface SearchPluginsResponse {
    plugins: any[];
    total: number;
    maxPage: number;
    page: number;
    pages: any[];
}

interface CreateValues {
    query: string;
    size: number;
}

export default () => {
    const uuid = ServerContext.useStoreState(state => state.server.data!.uuid);

    const { clearFlashes, clearAndAddHttpError } = useFlash();

    const [ query, setQuery ] = useState('');
    const [ page, setPage ] = useState(1);
    const [ size, setSize ] = useState(10);

    const { data, error } = useSWR<SearchPluginsResponse>([ uuid, size, page, query, '/plugins' ], (uuid, size, page, query) => searchPlugins(uuid, size, page, query), {
        revalidateOnFocus: false,
    });

    useEffect(() => {
        if (!error) {
            clearFlashes('server:plugins');
        } else {
            clearAndAddHttpError({ key: 'server:plugins', error });
        }
    }, [ error ]);

    const submit = ({ query, size }: CreateValues, { setSubmitting }: FormikHelpers<CreateValues>) => {
        setQuery(query);
        setSize(size);
        setPage(1);
        setSubmitting(false);
    };

    return (
        <ServerContentBlock title={'Plugin Installer'} css={tw`flex flex-wrap`}>
            <div css={tw`w-full`}>
                <FlashMessageRender byKey={'server:plugins'} css={tw`mb-4`} />

                <div css={tw`px-1 py-2`}>
                    <Formik
                        onSubmit={submit}
                        initialValues={{ query: '', size: 10 }}
                        validationSchema={object().shape({
                            query: string(),
                            size: number().required(),
                        })}
                    >
                        <Form>
                            <div css={tw`flex flex-wrap`}>
                                <div css={tw`w-full sm:w-8/12 sm:pr-4`}>
                                    <Field
                                        name={'query'}
                                        placeholder={'PVPUtils'}
                                    />
                                </div>
                                <div css={tw`w-full sm:w-4/12`}>
                                    <FormikFieldWrapper name={'size'}>
                                        <FormikField as={Select} name={'size'}>
                                            <option value={10}>10</option>
                                            <option value={25}>25</option>
                                            <option value={50}>50</option>
                                        </FormikField>
                                    </FormikFieldWrapper>
                                </div>
                            </div>
                        </Form>
                    </Formik>
                </div>
            </div>
            {!data ?
                <div css={tw`w-full`}>
                    <Spinner size={'large'} centered />
                </div>
                :
                <>
                    {data.plugins.length < 1 ?
                        <p css={tw`text-center text-sm text-neutral-400 pt-4 pb-4`}>
                            There are no plugins. Try to search again...
                        </p>
                        :
                        (
                            <>
                                {data.plugins.map((item, key) => (
                                    <div css={tw`w-full md:w-6/12 md:pl-2 md:pr-2 pt-4`} key={key}>
                                        <TitledGreyBox title={item.name}>
                                            <div css={tw`px-1 py-2`}>
                                                <div css={tw`flex flex-wrap`}>
                                                    <div css={tw`w-auto lg:w-2/12 pr-4`}>
                                                        <img css={'width: 48px; height: 48px;'} src={(item.icon.url === '' ? '//static.spigotmc.org/styles/spigot/xenresource/resource_icon.png' : `//www.spigotmc.org/${item.icon.url}`)} />
                                                    </div>
                                                    <div css={tw`w-auto lg:w-10/12`}>
                                                        {item.tag}
                                                    </div>
                                                </div>

                                                <span>
                                                    <FontAwesomeIcon css={`${item.rating.average >= 1 ? 'color: yellow;' : ''} ${tw`mt-4`}`} icon={faStar} />
                                                    <FontAwesomeIcon css={`${item.rating.average >= 2 ? 'color: yellow;' : ''} ${tw`mt-4`}`} icon={faStar} />
                                                    <FontAwesomeIcon css={`${item.rating.average >= 3 ? 'color: yellow;' : ''} ${tw`mt-4`}`} icon={faStar} />
                                                    <FontAwesomeIcon css={`${item.rating.average >= 4 ? 'color: yellow;' : ''} ${tw`mt-4`}`} icon={faStar} />
                                                    <FontAwesomeIcon css={`${item.rating.average >= 5 ? 'color: yellow;' : ''} ${tw`mt-4`}`} icon={faStar} />
                                                </span>

                                                {item.file.type !== 'external' &&
                                                <ManageButton pluginId={item.id} installed={item.installed}></ManageButton>
                                                }
                                                <LinkButton target={'_blank'} href={`https://api.spiget.org/v2/resources/${item.id}/go`} css={`float: right; margin-top: .8rem; ${tw`mr-1`}`} type={'button'} color={'primary'} size={'xsmall'}>
                                                    <FontAwesomeIcon icon={faExternalLinkAlt}/> View
                                                </LinkButton>
                                            </div>
                                        </TitledGreyBox>
                                    </div>
                                ))}
                                <div css={tw`w-full`}>
                                    <div css={tw`mt-4 flex justify-center`}>
                                        {page !== 1 &&
                                        <Block
                                            isSecondary
                                            color={'primary'}
                                            onClick={() => setPage(page - 1)}
                                        >
                                            <FontAwesomeIcon icon={faAngleDoubleLeft} />
                                        </Block>
                                        }
                                        {data.pages.map((item, key) => (
                                            <Block
                                                isSecondary={item !== page}
                                                color={'primary'}
                                                key={`plugins_page_${key}`}
                                                onClick={() => setPage(item)}
                                            >
                                                {item}
                                            </Block>
                                        ))}
                                        {page !== data.maxPage &&
                                        <Block
                                            isSecondary
                                            color={'primary'}
                                            onClick={() => setPage(page + 1)}
                                        >
                                            <FontAwesomeIcon icon={faAngleDoubleRight} />
                                        </Block>
                                        }
                                    </div>
                                </div>
                            </>
                        )
                    }
                </>
            }
        </ServerContentBlock>
    );
};
